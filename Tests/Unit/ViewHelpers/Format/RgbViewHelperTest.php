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

namespace MoveElevator\Styleguide\Tests\Unit\ViewHelpers\Format;

use InvalidArgumentException;
use MoveElevator\Styleguide\ViewHelpers\Format\RgbViewHelper;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * RgbViewHelperTest.
 *
 * @author Konrad Michalik <km@move-elevator.de>
 * @license GPL-2.0-or-later
 */
final class RgbViewHelperTest extends TestCase
{
    #[DataProvider('colorProvider')]
    public function testRenderConvertsHexToRgb(string $hex, string $expected): void
    {
        $this->assertSame($expected, $this->render($hex));
    }

    /**
     * @return iterable<string, array{string, string}>
     */
    public static function colorProvider(): iterable
    {
        yield 'orange' => ['#FF6418', '255, 100, 24'];
        yield 'black' => ['#000000', '0, 0, 0'];
        yield 'white' => ['#FFFFFF', '255, 255, 255'];
        yield 'lowercase without hash' => ['003241', '0, 50, 65'];
    }

    #[DataProvider('invalidColorProvider')]
    public function testRenderRejectsInvalidColors(string $hex): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->render($hex);
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function invalidColorProvider(): iterable
    {
        yield 'short notation' => ['#FFF'];
        yield 'not hexadecimal' => ['#GGGGGG'];
        yield 'empty' => [''];
    }

    private function render(string $hex): string
    {
        $viewHelper = new RgbViewHelper();
        $viewHelper->initializeArguments();
        $viewHelper->setArguments(['color' => $hex]);

        return $viewHelper->render();
    }
}
