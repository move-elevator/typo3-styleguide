<?php

declare(strict_types=1);

/*
 * This file is part of the "typo3_styleguide" TYPO3 CMS extension.
 *
 * (c) 2025-2026 Konrad Michalik <km@move-elevator.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MoveElevator\Styleguide\Middleware;

use MoveElevator\Styleguide\Configuration;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\{MiddlewareInterface, RequestHandlerInterface};
use Throwable;
use TYPO3\CMS\Core\Configuration\Exception\{ExtensionConfigurationExtensionNotConfiguredException, ExtensionConfigurationPathDoesNotExistException};
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Context\{Context, UserAspect};
use TYPO3\CMS\Core\Routing\PageArguments;
use TYPO3\CMS\Core\Utility\{GeneralUtility, RootlineUtility};
use TYPO3\CMS\Frontend\Controller\ErrorController;

/**
 * BackendUserOnlyAccessMiddleware.
 *
 * @author Konrad Michalik <km@move-elevator.de>
 * @license GPL-2.0-or-later
 */
class BackendUserOnlyAccessMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly Context $context,
        private readonly ExtensionConfiguration $extensionConfiguration,
        private readonly ErrorController $errorController,
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $restrictedRootPageUids = $this->getRestrictedRootPageUids();
        if ([] === $restrictedRootPageUids) {
            return $handler->handle($request);
        }

        $pageArguments = $request->getAttribute('routing');
        if (!$pageArguments instanceof PageArguments || $pageArguments->getPageId() <= 0) {
            return $handler->handle($request);
        }

        $rootlinePageUids = $this->resolveRootlinePageUids($pageArguments->getPageId());
        if ([] === array_intersect($restrictedRootPageUids, $rootlinePageUids)) {
            return $handler->handle($request);
        }

        $userAspect = $this->context->getAspect('backend.user');
        if ($userAspect instanceof UserAspect && $userAspect->isLoggedIn()) {
            return $handler->handle($request);
        }

        return $this->errorController->accessDeniedAction(
            $request,
            'This page is only accessible to logged-in backend users.',
        );
    }

    /**
     * @return array<int, int>
     */
    protected function resolveRootlinePageUids(int $pageId): array
    {
        try {
            $rootline = GeneralUtility::makeInstance(RootlineUtility::class, $pageId, '', $this->context)->get();
        } catch (Throwable) {
            return [];
        }

        return array_map(static fn (array $page): int => (int) $page['uid'], $rootline);
    }

    /**
     * @return array<int, int>
     */
    private function getRestrictedRootPageUids(): array
    {
        try {
            $isEnabled = (bool) ($this->extensionConfiguration->get(Configuration::EXT_KEY, 'restrictToBackendUsers') ?? false);
            $configuredUids = (string) ($this->extensionConfiguration->get(Configuration::EXT_KEY, 'restrictedRootPageUids') ?? '');
        } catch (ExtensionConfigurationExtensionNotConfiguredException|ExtensionConfigurationPathDoesNotExistException) {
            // Extension configuration has not been initialized yet, e.g. right after installing this
            // extension version, before the Install Tool synchronized ext_conf_template.txt defaults.
            return [];
        }

        if (!$isEnabled) {
            return [];
        }

        return array_values(array_unique(array_filter(array_map(
            static fn (string $uid): int => (int) trim($uid),
            explode(',', $configuredUids),
        ))));
    }
}
