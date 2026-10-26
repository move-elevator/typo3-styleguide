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

use function array_map;
use function max;
use function min;

/**
 * ContrastRatioViewHelper.
 *
 * Returns the WCAG 2.x contrast ratio of two HEX colors, from 1.0 to 21.0. The value is not
 * rounded, so a comparison like `{ratio} >= 4.5` never passes a pair that actually fails.
 *
 * @author Konrad Michalik <km@move-elevator.de>
 * @license GPL-2.0-or-later
 */
class ContrastRatioViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        $this->registerArgument('color', 'string', 'HEX Code', true);
        $this->registerArgument('background', 'string', 'HEX Code', true);
    }

    public function render(): float
    {
        $color = $this->relativeLuminance((string) $this->arguments['color']);
        $background = $this->relativeLuminance((string) $this->arguments['background']);

        return (max($color, $background) + 0.05) / (min($color, $background) + 0.05);
    }

    private function relativeLuminance(string $hexColor): float
    {
        $linear = array_map(
            static function (int $channel): float {
                $value = $channel / 255;

                return $value <= 0.03928 ? $value / 12.92 : (($value + 0.055) / 1.055) ** 2.4;
            },
            ColorUtility::hexToRgb($hexColor),
        );

        return 0.2126 * $linear[0] + 0.7152 * $linear[1] + 0.0722 * $linear[2];
    }
}
