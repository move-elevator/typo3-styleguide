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
use MoveElevator\Styleguide\ViewHelpers\{ContrastColorViewHelper, ContrastRatioViewHelper, IsHexColorViewHelper, RgbColorViewHelper};
use PHPUnit\Framework\TestCase;

/**
 * ColorViewHelpersTest.
 *
 * @author Konrad Michalik <km@move-elevator.de>
 * @license GPL-2.0-or-later
 */
final class ColorViewHelpersTest extends TestCase
{
    public function testRgbColorRendersCommaSeparatedChannels(): void
    {
        $viewHelper = new RgbColorViewHelper();
        $viewHelper->setArguments(['color' => '#FF6418']);

        $this->assertSame('255, 100, 24', $viewHelper->render());
    }

    public function testIsHexColorTellsValidFromInvalidValues(): void
    {
        $this->assertTrue($this->isHexColor('#FF6418'));
        $this->assertFalse($this->isHexColor('orange'));
    }

    public function testContrastRatioIsRoundedDownSoNoCriterionIsOverstated(): void
    {
        $this->assertSame(21.0, $this->contrastRatio('#FFFFFF', '#000000'));
        $this->assertSame(4.54, $this->contrastRatio('#767676', '#FFFFFF'));
        $this->assertSame(4.47, $this->contrastRatio('#777777', '#FFFFFF'));
    }

    public function testContrastColorReturnsBlackOnLightAndWhiteOnDark(): void
    {
        $this->assertSame('#000000', $this->contrastFor('#E4E2D4'));
        $this->assertSame('#FFFFFF', $this->contrastFor('#003241'));
    }

    public function testContrastColorKeepsExceptionCodeForInvalidHex(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionCode(4518636088);

        $this->contrastFor('#12345');
    }

    private function contrastRatio(string $color, string $against): float
    {
        $viewHelper = new ContrastRatioViewHelper();
        $viewHelper->setArguments(['color' => $color, 'against' => $against]);

        return $viewHelper->render();
    }

    private function isHexColor(string $color): bool
    {
        $viewHelper = new IsHexColorViewHelper();
        $viewHelper->setArguments(['color' => $color]);

        return $viewHelper->render();
    }

    private function contrastFor(string $color): string
    {
        $viewHelper = new ContrastColorViewHelper();
        $viewHelper->setArguments(['color' => $color]);

        return $viewHelper->render();
    }
}
