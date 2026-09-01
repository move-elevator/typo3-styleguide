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

namespace MoveElevator\Styleguide\Tests\Unit\Middleware;

use MoveElevator\Styleguide\Configuration;
use MoveElevator\Styleguide\Middleware\BackendUserOnlyAccessMiddleware;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Authentication\AbstractUserAuthentication;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Context\{Context, UserAspect};
use TYPO3\CMS\Core\Routing\PageArguments;
use TYPO3\CMS\Frontend\Controller\ErrorController;

/**
 * BackendUserOnlyAccessMiddlewareTest.
 *
 * @author Konrad Michalik <km@move-elevator.de>
 * @license GPL-2.0-or-later
 */
final class BackendUserOnlyAccessMiddlewareTest extends TestCase
{
    private ExtensionConfiguration&MockObject $extensionConfiguration;
    private Context&MockObject $context;
    private ErrorController&MockObject $errorController;

    protected function setUp(): void
    {
        parent::setUp();

        $this->extensionConfiguration = $this->createMock(ExtensionConfiguration::class);
        $this->context = $this->createMock(Context::class);
        $this->errorController = $this->createMock(ErrorController::class);
    }

    public function testPassesThroughWhenFeatureIsDisabled(): void
    {
        $this->configureExtension(false, '10');

        $request = $this->createRequestForPage(10);
        $response = $this->createMock(ResponseInterface::class);
        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->expects(self::once())->method('handle')->with($request)->willReturn($response);

        $subject = $this->createSubject(rootlinePageUids: [10]);
        self::assertSame($response, $subject->process($request, $handler));
    }

    public function testPassesThroughWhenNoRootPageUidsAreConfigured(): void
    {
        $this->configureExtension(true, '');

        $request = $this->createRequestForPage(10);
        $response = $this->createMock(ResponseInterface::class);
        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->expects(self::once())->method('handle')->with($request)->willReturn($response);

        $subject = $this->createSubject(rootlinePageUids: [10]);
        self::assertSame($response, $subject->process($request, $handler));
    }

    public function testPassesThroughWhenPageIsNotInRestrictedScope(): void
    {
        $this->configureExtension(true, '10');

        $request = $this->createRequestForPage(20);
        $response = $this->createMock(ResponseInterface::class);
        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->expects(self::once())->method('handle')->with($request)->willReturn($response);

        $subject = $this->createSubject(rootlinePageUids: [1, 2, 20]);
        self::assertSame($response, $subject->process($request, $handler));
    }

    public function testPassesThroughWhenPageIdCannotBeResolved(): void
    {
        $this->configureExtension(true, '10');

        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getAttribute')->with('routing')->willReturn(null);

        $response = $this->createMock(ResponseInterface::class);
        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->expects(self::once())->method('handle')->with($request)->willReturn($response);

        $subject = $this->createSubject(rootlinePageUids: [10]);
        self::assertSame($response, $subject->process($request, $handler));
    }

    public function testDeniesAnonymousUserOnRestrictedPageItself(): void
    {
        $this->configureExtension(true, '10');
        $this->context->method('getAspect')->with('backend.user')->willReturn(new UserAspect(null));

        $request = $this->createRequestForPage(10);
        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->expects(self::never())->method('handle');

        $deniedResponse = $this->createMock(ResponseInterface::class);
        $this->errorController->expects(self::once())
            ->method('accessDeniedAction')
            ->with($request, self::isType('string'))
            ->willReturn($deniedResponse);

        $subject = $this->createSubject(rootlinePageUids: [10]);
        self::assertSame($deniedResponse, $subject->process($request, $handler));
    }

    public function testDeniesAnonymousUserOnPageBelowRestrictedRoot(): void
    {
        $this->configureExtension(true, '10');
        $this->context->method('getAspect')->with('backend.user')->willReturn(new UserAspect(null));

        $request = $this->createRequestForPage(55);
        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->expects(self::never())->method('handle');

        $deniedResponse = $this->createMock(ResponseInterface::class);
        $this->errorController->expects(self::once())
            ->method('accessDeniedAction')
            ->willReturn($deniedResponse);

        $subject = $this->createSubject(rootlinePageUids: [1, 10, 55]);
        self::assertSame($deniedResponse, $subject->process($request, $handler));
    }

    public function testPassesThroughForLoggedInBackendUser(): void
    {
        $this->configureExtension(true, '10');

        $backendUser = $this->createMock(AbstractUserAuthentication::class);
        $backendUser->userid_column = 'uid';
        $backendUser->user = ['uid' => 1];
        $this->context->method('getAspect')->with('backend.user')->willReturn(new UserAspect($backendUser));

        $request = $this->createRequestForPage(10);
        $response = $this->createMock(ResponseInterface::class);
        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->expects(self::once())->method('handle')->with($request)->willReturn($response);

        $this->errorController->expects(self::never())->method('accessDeniedAction');

        $subject = $this->createSubject(rootlinePageUids: [10]);
        self::assertSame($response, $subject->process($request, $handler));
    }

    public function testPassesThroughWhenExtensionConfigurationHasNotBeenInitializedYet(): void
    {
        $this->extensionConfiguration->method('get')
            ->willThrowException(new ExtensionConfigurationExtensionNotConfiguredException('not configured', 1509654728));

        $request = $this->createRequestForPage(10);
        $response = $this->createMock(ResponseInterface::class);
        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->expects(self::once())->method('handle')->with($request)->willReturn($response);

        $subject = $this->createSubject(rootlinePageUids: [10]);
        self::assertSame($response, $subject->process($request, $handler));
    }

    private function configureExtension(bool $enabled, string $restrictedRootPageUids): void
    {
        $this->extensionConfiguration->method('get')
            ->willReturnMap([
                [Configuration::EXT_KEY, 'restrictToBackendUsers', $enabled],
                [Configuration::EXT_KEY, 'restrictedRootPageUids', $restrictedRootPageUids],
            ]);
    }

    private function createRequestForPage(int $pageId): ServerRequestInterface&MockObject
    {
        $pageArguments = $this->createMock(PageArguments::class);
        $pageArguments->method('getPageId')->willReturn($pageId);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getAttribute')->with('routing')->willReturn($pageArguments);

        return $request;
    }

    /**
     * @param array<int, int> $rootlinePageUids
     */
    private function createSubject(array $rootlinePageUids): BackendUserOnlyAccessMiddleware
    {
        return new class($this->context, $this->extensionConfiguration, $this->errorController, $rootlinePageUids) extends BackendUserOnlyAccessMiddleware {
            /**
             * @param array<int, int> $rootlinePageUids
             */
            public function __construct(
                Context $context,
                ExtensionConfiguration $extensionConfiguration,
                ErrorController $errorController,
                private readonly array $rootlinePageUids,
            ) {
                parent::__construct($context, $extensionConfiguration, $errorController);
            }

            protected function resolveRootlinePageUids(int $pageId): array
            {
                return $this->rootlinePageUids;
            }
        };
    }
}
