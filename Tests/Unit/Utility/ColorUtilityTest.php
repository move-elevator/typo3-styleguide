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
