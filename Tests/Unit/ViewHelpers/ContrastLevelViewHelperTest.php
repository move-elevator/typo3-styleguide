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

use MoveElevator\Styleguide\ViewHelpers\ContrastLevelViewHelper;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * ContrastLevelViewHelperTest.
 *
 * @author Konrad Michalik <km@move-elevator.de>
 * @license GPL-2.0-or-later
 */
final class ContrastLevelViewHelperTest extends TestCase
{
    #[DataProvider('ratioProvider')]
    public function testRenderMapsRatioToWcagLevel(float $ratio, string $expected): void
    {
        $viewHelper = new ContrastLevelViewHelper();
        $viewHelper->initializeArguments();
        $viewHelper->setArguments(['ratio' => $ratio]);

        $this->assertSame($expected, $viewHelper->render());
    }

    /**
     * @return iterable<string, array{float, string}>
     */
    public static function ratioProvider(): iterable
    {
        yield 'maximum' => [21.0, 'aaa'];
        yield 'AAA threshold' => [7.0, 'aaa'];
        yield 'just below AAA' => [6.99, 'aa'];
        yield 'AA threshold' => [4.5, 'aa'];
        yield 'just below AA' => [4.49, 'large'];
        yield 'large text threshold' => [3.0, 'large'];
        yield 'just below large text' => [2.99, 'fail'];
        yield 'minimum' => [1.0, 'fail'];
    }
}
