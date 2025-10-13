<?php

declare(strict_types=1);

/*
 * This file is part of the "typo3_styleguide" TYPO3 CMS extension.
 *
 * (c) 2025 Konrad Michalik <km@move-elevator.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MoveElevator\Styleguide\ViewHelpers\Render;

use Exception;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use UnexpectedValueException;

use function is_array;
use function is_bool;

/**
 * TemplateViewHelper.
 *
 * @author Konrad Michalik <km@move-elevator.de>
 * @license GPL-2.0-or-later
 */
class TemplateViewHelper extends AbstractViewHelper
{
    /**
     * @var bool
     */
    protected $escapeOutput = false;

    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('file', 'string', 'Path to template file, EXT:myext/... paths supported');
        $this->registerArgument('variables', 'array', 'Optional array of template variables for rendering');
        $this->registerArgument('format', 'string', 'Optional format of the template(s) being rendered');
        $this->registerArgument(
            'paths',
            'array',
            'Optional array of arrays of layout and partial root paths, EXT:mypath/... paths supported',
        );
    }

    public function render(): string
    {
        /** @var string|null $file */
        $file = $this->arguments['file'];
        if (null === $file) {
            /** @var string|null $file */
            $file = $this->renderChildren();
        }

        $file = GeneralUtility::getFileAbsFileName((string) $file);
        $view = static::getPreparedView();
        /** @var \Psr\Http\Message\ServerRequestInterface $request */
        $request = self::resolveRequestFromRenderingContext($this->renderingContext);
        $view->setRequest($request); // @phpstan-ignore method.deprecatedClass
        $view->setTemplatePathAndFilename($file); // @phpstan-ignore method.deprecatedClass
        if (is_array($this->arguments['variables'])) {
            $view->assignMultiple($this->arguments['variables']);
        }
        /** @var string|null $format */
        $format = $this->arguments['format'];
        if (null !== $format) {
            $view->setFormat($format); // @phpstan-ignore method.deprecatedClass
        }
        $paths = $this->arguments['paths'];
        if (is_array($paths)) {
            if (isset($paths['layoutRootPaths']) && is_array($paths['layoutRootPaths'])) {
                $layoutRootPaths = $this->processPathsArray($paths['layoutRootPaths']);
                $view->setLayoutRootPaths($layoutRootPaths); // @phpstan-ignore method.deprecatedClass
            }
            if (isset($paths['partialRootPaths']) && is_array($paths['partialRootPaths'])) {
                $partialRootPaths = $this->processPathsArray($paths['partialRootPaths']);
                $view->setPartialRootPaths($partialRootPaths); // @phpstan-ignore method.deprecatedClass
            }
        }

        return static::renderView($view, $this->arguments);
    }

    public static function resolveRequestFromRenderingContext(RenderingContextInterface $renderingContext): object
    {
        $request = null;
        if (method_exists($renderingContext, 'getRequest')) {
            $request = $renderingContext->getRequest();
        } elseif (method_exists($renderingContext, 'getControllerContext')) {
            $request = $renderingContext->getControllerContext()->getRequest();
        }
        if (!$request) {
            throw new UnexpectedValueException('Unable to resolve request from RenderingContext', 1673191812);
        }

        return $request;
    }

    /**
     * @param array<int|string, string> $paths
     *
     * @return array<int|string, string>
     */
    protected function processPathsArray(array $paths): array
    {
        $pathsArray = [];
        foreach ($paths as $key => $path) {
            $pathsArray[$key] = (str_starts_with($path, 'EXT:')) ? GeneralUtility::getFileAbsFileName($path) : $path;
        }

        return $pathsArray;
    }

    /**
     * @param array<string, mixed> $arguments
     */
    protected static function renderView(StandaloneView $view, array $arguments): string // @phpstan-ignore parameter.deprecatedClass
    {
        try {
            /** @var string|null $content */
            $content = $view->render();
        } catch (Exception $error) {
            $graceful = $arguments['graceful'] ?? false;
            if (!is_bool($graceful) || !$graceful) {
                throw $error;
            }
            $content = $error->getMessage().' ('.$error->getCode().')';
        }

        return (string) $content;
    }

    protected static function getPreparedView(): StandaloneView // @phpstan-ignore return.deprecatedClass
    {
        /** @var StandaloneView $view */
        $view = GeneralUtility::makeInstance(StandaloneView::class); // @phpstan-ignore classConstant.deprecatedClass

        return $view;
    }
}
