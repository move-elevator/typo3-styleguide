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

use function array_filter;
use function array_map;
use function array_values;
use function explode;
use function fnmatch;
use function is_dir;
use function is_file;
use function scandir;
use function trim;

/**
 * FileListUtility.
 *
 * @author Konrad Michalik <km@move-elevator.de>
 * @license GPL-2.0-or-later
 */
final class FileListUtility
{
    /**
     * Lists the files of a directory, skipping those matching one of the exclude patterns.
     *
     * @param string $excludePatterns comma-separated list of glob patterns matched against the file name
     *
     * @return list<string>
     */
    public static function listFiles(string $absoluteDirectory, string $excludePatterns = ''): array
    {
        if ('' === $absoluteDirectory || !is_dir($absoluteDirectory)) {
            return [];
        }

        $entries = scandir($absoluteDirectory);
        if (false === $entries) {
            return [];
        }

        $patterns = array_values(array_filter(
            array_map(trim(...), explode(',', $excludePatterns)),
            static fn (string $pattern): bool => '' !== $pattern,
        ));

        $files = [];
        foreach ($entries as $entry) {
            if ('.' === $entry || '..' === $entry || !is_file($absoluteDirectory.'/'.$entry)) {
                continue;
            }

            if (self::isExcluded($entry, $patterns)) {
                continue;
            }

            $files[] = $entry;
        }

        return $files;
    }

    /**
     * @param list<string> $patterns
     */
    private static function isExcluded(string $fileName, array $patterns): bool
    {
        foreach ($patterns as $pattern) {
            if (fnmatch($pattern, $fileName)) {
                return true;
            }
        }

        return false;
    }
}
