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

namespace MoveElevator\Styleguide\ViewHelpers;

use Closure;
use InvalidArgumentException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * FilesViewHelper.
 *
 * @author Konrad Michalik <km@move-elevator.de>
 * @license GPL-2.0
 */
class FilesViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        $this->registerArgument('path', 'string', 'Path to the directory to list files from', true);
    }

    /**
     * @param array<string, mixed> $arguments
     *
     * @return list<string>
     */
    public static function renderStatic(array $arguments, Closure $renderChildrenClosure, RenderingContextInterface $renderingContext): array
    {
        $path = GeneralUtility::getFileAbsFileName($arguments['path']);
        if (!is_dir($path)) {
            throw new InvalidArgumentException('The provided path is not a valid directory: '.$path, 4247501749);
        }

        $files = [];
        foreach (scandir($path) as $file) {
            if ('.' !== $file && '..' !== $file && is_file($path.'/'.$file)) {
                $files[] = $file;
            }
        }

        return $files;
    }
}
