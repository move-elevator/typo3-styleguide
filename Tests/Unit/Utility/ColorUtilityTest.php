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

namespace MoveElevator\Styleguide\Tests\Unit\Utility;

use InvalidArgumentException;
use MoveElevator\Styleguide\Utility\ColorUtility;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * ColorUtilityTest.
 *
 * @author Konrad Michalik <km@move-elevator.de>
 * @license GPL-2.0-or-later
 */
final class ColorUtilityTest extends TestCase
{
    /**
     * @param array{int, int, int} $expected
     */
    #[DataProvider('validHexProvider')]
    public function testHexToRgbConvertsValidHex(string $hex, array $expected): void
    {
        $this->assertSame($expected, ColorUtility::hexToRgb($hex));
    }

    /**
     * @return iterable<string, array{string, array{int, int, int}}>
     */
    public static function validHexProvider(): iterable
    {
        yield 'with hash' => ['#FF6418', [255, 100, 24]];
        yield 'without hash' => ['003241', [0, 50, 65]];
        yield 'lowercase' => ['#e4e2d4', [228, 226, 212]];
    }

    #[DataProvider('validHexProvider')]
    public function testIsHexAcceptsValidHex(string $hex): void
    {
        $this->assertTrue(ColorUtility::isHex($hex));
    }

    #[DataProvider('contrastPairProvider')]
    public function testContrastRatioMatchesKnownPairs(string $first, string $second, float $expected): void
    {
        $this->assertEqualsWithDelta($expected, ColorUtility::contrastRatio($first, $second), 0.01);
    }

    /**
     * @return iterable<string, array{string, string, float}>
     */
    public static function contrastPairProvider(): iterable
    {
        yield 'black on white' => ['#FFFFFF', '#000000', 21.0];
        yield 'order does not matter' => ['#000000', '#FFFFFF', 21.0];
        yield 'identical colors' => ['#FF6418', '#FF6418', 1.0];
        yield 'just above AA for text' => ['#767676', '#FFFFFF', 4.54];
        yield 'just below AA for text' => ['#777777', '#FFFFFF', 4.48];
    }

    public function testContrastRatioRejectsInvalidColor(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionCode(4518636088);

        ColorUtility::contrastRatio('#FF6418', 'black');
    }

    #[DataProvider('invalidHexProvider')]
    public function testIsHexRejectsInvalidHex(string $hex): void
    {
        $this->assertFalse(ColorUtility::isHex($hex));
    }

    #[DataProvider('invalidHexProvider')]
    public function testHexToRgbRejectsInvalidHex(string $hex): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionCode(4518636088);

        ColorUtility::hexToRgb($hex);
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function invalidHexProvider(): iterable
    {
        yield 'empty' => [''];
        yield 'five digits' => ['#12345'];
        yield 'non hex characters' => ['#GGGGGG'];
        yield 'six letter color name' => ['orange'];
        yield 'trailing newline' => ["#FF6418\n"];
    }
}
