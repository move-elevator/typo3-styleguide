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

use MoveElevator\Styleguide\Utility\PreviewUrl;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * PreviewUrlTest.
 *
 * @author Konrad Michalik <km@move-elevator.de>
 * @license GPL-2.0-or-later
 */
final class PreviewUrlTest extends TestCase
{
    #[DataProvider('urls')]
    public function testWithoutTrailingSlash(string $url, string $expected): void
    {
        self::assertSame($expected, PreviewUrl::withoutTrailingSlash($url));
    }

    /**
     * @return iterable<string, array{string, string}>
     */
    public static function urls(): iterable
    {
        yield 'path with query' => ['/online-styleguide/?sg_preview=41', '/online-styleguide?sg_preview=41'];
        yield 'path only' => ['/online-styleguide/', '/online-styleguide'];
        yield 'no slash at the end' => ['/page?sg_preview=1', '/page?sg_preview=1'];
        yield 'host only stays' => ['/', '/'];
        yield 'host only with query stays' => ['/?sg_preview=1', '/?sg_preview=1'];
        yield 'absolute url' => ['https://example.test/base/?sg_preview=3', 'https://example.test/base?sg_preview=3'];
        yield 'slash inside the path stays' => ['/a/b/c?x=1/', '/a/b/c?x=1/'];
    }
}
