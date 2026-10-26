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

namespace MoveElevator\Styleguide\Utility;

use InvalidArgumentException;

use function hexdec;
use function ltrim;
use function preg_match;
use function str_split;

/**
 * ColorUtility.
 *
 * @author Konrad Michalik <km@move-elevator.de>
 * @license GPL-2.0-or-later
 */
final class ColorUtility
{
    /**
     * @return array{int, int, int}
     */
    public static function hexToRgb(string $hexColor): array
    {
        $hex = ltrim($hexColor, '#');
        if (1 !== preg_match('/^[0-9a-f]{6}$/i', $hex)) {
            throw new InvalidArgumentException('Invalid HEX color code: '.$hexColor, 1761475200);
        }

        [$red, $green, $blue] = str_split($hex, 2);

        return [(int) hexdec($red), (int) hexdec($green), (int) hexdec($blue)];
    }
}
