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

use MoveElevator\Styleguide\Utility\FileListUtility;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * FileListUtilityTest.
 *
 * @author Konrad Michalik <km@move-elevator.de>
 * @license GPL-2.0-or-later
 */
final class FileListUtilityTest extends TestCase
{
    private const FIXTURE_DIR = __DIR__.'/../Fixtures/Icons';

    public function testListFilesReturnsAllFilesWithoutExcludePatterns(): void
    {
        $this->assertSame(
            ['README.md', 'icon-home.svg', 'icon-user-alt.svg', 'icon-user.svg', 'legacy-star.svg', 'sprite.json'],
            FileListUtility::listFiles(self::FIXTURE_DIR),
        );
    }

    public function testListFilesSkipsSubdirectories(): void
    {
        $this->assertNotContains('subdir', FileListUtility::listFiles(self::FIXTURE_DIR));
    }

    /**
     * @param list<string> $expected
     */
    #[DataProvider('excludePatternProvider')]
    public function testListFilesSkipsMatchingFiles(string $excludePatterns, array $expected): void
    {
        $this->assertSame($expected, FileListUtility::listFiles(self::FIXTURE_DIR, $excludePatterns));
    }

    /**
     * @return iterable<string, array{string, list<string>}>
     */
    public static function excludePatternProvider(): iterable
    {
        yield 'exact file name' => [
            'README.md',
            ['icon-home.svg', 'icon-user-alt.svg', 'icon-user.svg', 'legacy-star.svg', 'sprite.json'],
        ];
        yield 'prefix pattern' => [
            'legacy-*',
            ['README.md', 'icon-home.svg', 'icon-user-alt.svg', 'icon-user.svg', 'sprite.json'],
        ];
        yield 'suffix pattern' => [
            '*-alt.svg',
            ['README.md', 'icon-home.svg', 'icon-user.svg', 'legacy-star.svg', 'sprite.json'],
        ];
        yield 'extension pattern' => [
            '*.json',
            ['README.md', 'icon-home.svg', 'icon-user-alt.svg', 'icon-user.svg', 'legacy-star.svg'],
        ];
        yield 'single character wildcard' => [
            'sprite.jso?',
            ['README.md', 'icon-home.svg', 'icon-user-alt.svg', 'icon-user.svg', 'legacy-star.svg'],
        ];
        yield 'multiple patterns with surrounding whitespace' => [
            '  *-alt.svg ,  legacy-* , README.md  ',
            ['icon-home.svg', 'icon-user.svg', 'sprite.json'],
        ];
        yield 'empty and blank patterns are ignored' => [
            ' , ,, ',
            ['README.md', 'icon-home.svg', 'icon-user-alt.svg', 'icon-user.svg', 'legacy-star.svg', 'sprite.json'],
        ];
        yield 'pattern matching everything' => [
            '*',
            [],
        ];
        yield 'pattern matching nothing' => [
            'does-not-exist.svg',
            ['README.md', 'icon-home.svg', 'icon-user-alt.svg', 'icon-user.svg', 'legacy-star.svg', 'sprite.json'],
        ];
        yield 'matching is case sensitive' => [
            '*.SVG',
            ['README.md', 'icon-home.svg', 'icon-user-alt.svg', 'icon-user.svg', 'legacy-star.svg', 'sprite.json'],
        ];
    }

    public function testListFilesReturnsEmptyArrayForNonExistentDirectory(): void
    {
        $this->assertSame([], FileListUtility::listFiles(self::FIXTURE_DIR.'/does-not-exist'));
    }

    public function testListFilesReturnsEmptyArrayForEmptyPath(): void
    {
        $this->assertSame([], FileListUtility::listFiles(''));
    }

    public function testListFilesReturnsEmptyArrayForFilePath(): void
    {
        $this->assertSame([], FileListUtility::listFiles(self::FIXTURE_DIR.'/README.md'));
    }
}
