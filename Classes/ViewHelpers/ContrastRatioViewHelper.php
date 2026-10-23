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

use function floor;

/**
 * ContrastRatioViewHelper.
 *
 * Returns the WCAG contrast ratio of two colors, rounded down to two decimals so a ratio below a threshold
 * is never displayed as reaching it.
 *
 * @author Konrad Michalik <km@move-elevator.de>
 * @license GPL-2.0-or-later
 */
class ContrastRatioViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        $this->registerArgument('color', 'string', 'HEX Code', true);
        $this->registerArgument('against', 'string', 'HEX Code of the color to compare with', true);
    }

    public function render(): float
    {
        $ratio = ColorUtility::contrastRatio((string) $this->arguments['color'], (string) $this->arguments['against']);

        return floor($ratio * 100) / 100;
    }
}
