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

use function implode;

/**
 * RgbColorViewHelper.
 *
 * Converts a HEX color to its comma-separated RGB channels, e.g. `#FF6418` to `255, 100, 24`.
 *
 * @author Konrad Michalik <km@move-elevator.de>
 * @license GPL-2.0-or-later
 */
class RgbColorViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        $this->registerArgument('color', 'string', 'HEX Code', true);
    }

    public function render(): string
    {
        return implode(', ', ColorUtility::hexToRgb((string) $this->arguments['color']));
    }
}
