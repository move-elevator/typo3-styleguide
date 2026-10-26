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

namespace MoveElevator\Styleguide\Tests\Unit\ViewHelpers;

use InvalidArgumentException;
use MoveElevator\Styleguide\ViewHelpers\ContrastRatioViewHelper;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * ContrastRatioViewHelperTest.
 *
 * @author Konrad Michalik <km@move-elevator.de>
 * @license GPL-2.0-or-later
 */
final class ContrastRatioViewHelperTest extends TestCase
{
    #[DataProvider('colorPairProvider')]
    public function testRenderReturnsWcagContrastRatio(string $color, string $background, float $expected): void
    {
        $this->assertEqualsWithDelta($expected, $this->render($color, $background), 0.005);
    }

    /**
     * @return iterable<string, array{string, string, float}>
     */
    public static function colorPairProvider(): iterable
    {
        yield 'black on white' => ['#000000', '#FFFFFF', 21.0];
        yield 'identical colors' => ['#2563EB', '#2563EB', 1.0];
        yield 'orange on white' => ['#FF6418', '#FFFFFF', 2.96];
        yield 'dark blue on white' => ['#003241', '#FFFFFF', 13.70];
        yield 'lowercase without hash' => ['ff6418', 'ffffff', 2.96];
    }

    public function testRenderIsIndependentOfArgumentOrder(): void
    {
        $this->assertSame($this->render('#FF6418', '#003241'), $this->render('#003241', '#FF6418'));
    }

    public function testRenderDoesNotRoundUpBelowThreshold(): void
    {
        // #777777 on white is 4.48:1. Rounding to one decimal would pass AA (4.5:1) by mistake.
        $this->assertLessThan(4.5, $this->render('#777777', '#FFFFFF'));
    }

    #[DataProvider('invalidColorProvider')]
    public function testRenderRejectsInvalidColors(string $color): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->render($color, '#FFFFFF');
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

    private function render(string $color, string $background): float
    {
        $viewHelper = new ContrastRatioViewHelper();
        $viewHelper->initializeArguments();
        $viewHelper->setArguments(['color' => $color, 'background' => $background]);

        return $viewHelper->render();
    }
}
