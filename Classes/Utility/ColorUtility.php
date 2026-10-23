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

use function array_map;
use function hexdec;
use function max;
use function min;
use function preg_match;
use function substr;

/**
 * ColorUtility.
 *
 * @author Konrad Michalik <km@move-elevator.de>
 * @license GPL-2.0-or-later
 */
final class ColorUtility
{
    private const HEX_PATTERN = '/^#?([0-9a-fA-F]{6})\z/';

    public static function isHex(string $hex): bool
    {
        return 1 === preg_match(self::HEX_PATTERN, $hex);
    }

    /**
     * Contrast ratio of two colors according to WCAG 2.x, from 1 (identical) to 21 (black on white).
     *
     * @throws InvalidArgumentException if one of the values is not a valid HEX color
     */
    public static function contrastRatio(string $first, string $second): float
    {
        $firstLuminance = self::relativeLuminance($first);
        $secondLuminance = self::relativeLuminance($second);

        return (max($firstLuminance, $secondLuminance) + 0.05) / (min($firstLuminance, $secondLuminance) + 0.05);
    }

    /**
     * Converts a six digit HEX color, with or without leading hash, to its RGB channels.
     *
     * @return array{int, int, int}
     *
     * @throws InvalidArgumentException if the value is not a valid HEX color
     */
    public static function hexToRgb(string $hex): array
    {
        if (1 !== preg_match(self::HEX_PATTERN, $hex, $matches)) {
            throw new InvalidArgumentException('Invalid HEX color code: '.$hex, 4518636088);
        }

        $digits = $matches[1];

        return [
            (int) hexdec(substr($digits, 0, 2)),
            (int) hexdec(substr($digits, 2, 2)),
            (int) hexdec(substr($digits, 4, 2)),
        ];
    }

    private static function relativeLuminance(string $hex): float
    {
        [$red, $green, $blue] = array_map(static function (int $channel): float {
            $value = $channel / 255;

            return $value <= 0.03928 ? $value / 12.92 : (($value + 0.055) / 1.055) ** 2.4;
        }, self::hexToRgb($hex));

        return 0.2126 * $red + 0.7152 * $green + 0.0722 * $blue;
    }
}
