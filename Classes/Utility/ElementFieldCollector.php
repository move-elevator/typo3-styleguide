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

use function array_key_exists;
use function array_map;
use function explode;
use function in_array;
use function is_array;
use function is_string;
use function preg_match;
use function str_contains;
use function str_starts_with;
use function trim;

/**
 * ElementFieldCollector.
 *
 * Lists the fields an editor fills in for a content type, read from its TCA. Content Blocks and
 * hand-written types look the same here, because both end up in the TCA.
 *
 * @author Konrad Michalik <km@move-elevator.de>
 * @license GPL-2.0-or-later
 */
final class ElementFieldCollector
{
    /**
     * Palettes every content type has, so they say nothing about this one.
     */
    private const STANDARD_PALETTES = ['general', 'header', 'frames', 'language', 'hidden', 'access', 'visibility'];

    /**
     * Tabs every content type has, matched on the language key of the tab. Tabs a site adds are kept.
     */
    private const STANDARD_TAB = '/:(?:tabs\.)?(?:language|access|categories|notes|appearance|accessibility)$/';

    /**
     * @param array<string, mixed>     $tca       the TCA of the table, for content `$GLOBALS['TCA']['tt_content']`
     * @param callable(string): string $translate resolves a language key to its text
     *
     * @return list<array{name: string, required: bool, note: string}>
     */
    public function collect(string $type, array $tca, callable $translate): array
    {
        $showitem = $tca['types'][$type]['showitem'] ?? null;
        if (!is_string($showitem)) {
            return [];
        }

        $fields = [];
        foreach ($this->fieldEntries($showitem, $tca) as $entry) {
            [$field, $label] = $entry;
            $column = $tca['columns'][$field] ?? null;
            if (!is_array($column) || 'passthrough' === ($column['config']['type'] ?? null)) {
                continue;
            }

            $override = $tca['types'][$type]['columnsOverrides'][$field] ?? [];
            $config = array_replace_recursive($column['config'] ?? [], $override['config'] ?? []);
            $name = (string) ($override['label'] ?? ('' !== $label ? $label : ($column['label'] ?? '')));
            $note = (string) ($override['description'] ?? $column['description'] ?? '');

            $fields[] = [
                'name' => '' !== $name ? $translate($name) : $field,
                'required' => true === ($config['required'] ?? false) || str_contains((string) ($config['eval'] ?? ''), 'required'),
                'note' => '' !== $note ? $translate($note) : '',
            ];
        }

        return $fields;
    }

    /**
     * @param array<string, mixed> $tca
     *
     * @return list<array{string, string}> field name and the label written into the showitem entry
     */
    private function fieldEntries(string $showitem, array $tca): array
    {
        $entries = [];
        $inStandardTab = false;
        foreach (array_map(trim(...), explode(',', $showitem)) as $item) {
            if (str_starts_with($item, '--div--')) {
                $inStandardTab = 1 === preg_match(self::STANDARD_TAB, $item);

                continue;
            }
            if ('' === $item || $inStandardTab || str_starts_with($item, '--linebreak--')) {
                continue;
            }

            $parts = explode(';', $item);
            if ('--palette--' === $parts[0]) {
                $palette = $parts[2] ?? '';
                if (in_array($palette, self::STANDARD_PALETTES, true) || !array_key_exists($palette, $tca['palettes'] ?? [])) {
                    continue;
                }
                foreach ($this->fieldEntries((string) $tca['palettes'][$palette]['showitem'], $tca) as $paletteEntry) {
                    $entries[$paletteEntry[0]] ??= $paletteEntry;
                }

                continue;
            }

            $entries[$parts[0]] ??= [$parts[0], $parts[1] ?? ''];
        }

        return array_values($entries);
    }
}
