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

use MoveElevator\Styleguide\ViewHelpers\Format\SplitViewHelper;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * SplitViewHelperTest.
 *
 * @author Konrad Michalik <km@move-elevator.de>
 * @license GPL-2.0-or-later
 */
final class SplitViewHelperTest extends TestCase
{
    /**
     * @param list<string> $expected
     */
    #[DataProvider('splitProvider')]
    public function testSplitTrimsPartsAndDropsEmptyOnes(string $value, array $expected): void
    {
        $this->assertSame($expected, SplitViewHelper::split($value));
    }

    /**
     * @return iterable<string, array{string, list<string>}>
     */
    public static function splitProvider(): iterable
    {
        yield 'list with spaces' => ['400, 700', ['400', '700']];
        yield 'empty parts are dropped' => ['400,,700, ,', ['400', '700']];
        yield 'single value' => [' 700 ', ['700']];
        yield 'empty string' => ['', []];
    }
}
