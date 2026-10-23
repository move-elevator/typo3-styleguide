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

namespace MoveElevator\Styleguide\ViewHelpers;

use MoveElevator\Styleguide\Utility\ColorUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * ContrastColorViewHelper.
 *
 * @author Konrad Michalik <km@move-elevator.de>
 * @license GPL-2.0-or-later
 */
class ContrastColorViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        $this->registerArgument('color', 'string', 'HEX Code', true);
    }

    public function render(): string
    {
        [$r, $g, $b] = ColorUtility::hexToRgb((string) $this->arguments['color']);

        $brightness = ($r * 0.299 + $g * 0.587 + $b * 0.114);

        return $brightness > 128 ? '#000000' : '#FFFFFF';
    }
}
