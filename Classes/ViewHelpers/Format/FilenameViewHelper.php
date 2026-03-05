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

namespace MoveElevator\Styleguide\ViewHelpers\Format;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * FilenameViewHelper.
 *
 * @author Konrad Michalik <km@move-elevator.de>
 * @license GPL-2.0-or-later
 */
class FilenameViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        $this->registerArgument('filename', 'string', 'String to format');
    }

    public function render(): string
    {
        $value = $this->renderChildren();

        return pathinfo((string) $value, \PATHINFO_FILENAME);
    }
}
