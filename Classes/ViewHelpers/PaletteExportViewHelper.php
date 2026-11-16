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

namespace MoveElevator\Styleguide\ViewHelpers;

use InvalidArgumentException;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

use function array_map;
use function in_array;
use function is_array;
use function json_encode;
use function ltrim;
use function preg_match;
use function preg_replace;
use function strtoupper;

use const JSON_PRETTY_PRINT;
use const JSON_THROW_ON_ERROR;
use const JSON_UNESCAPED_SLASHES;

/**
 * PaletteExportViewHelper.
 *
 * Renders the color records as SCSS variables, LESS variables or a JSON file in the design tokens format.
 * Colors without a valid HEX value or variable name are skipped.
 *
 * @author Konrad Michalik <km@move-elevator.de>
 * @license GPL-2.0-or-later
 */
class PaletteExportViewHelper extends AbstractViewHelper
{
    private const FORMATS = ['scss', 'less', 'json'];

    public function initializeArguments(): void
    {
        $this->registerArgument('colors', 'array', 'Color records as returned by the DatabaseQueryProcessor', true);
        $this->registerArgument('format', 'string', 'Export format: scss, less or json', true);
    }

    public function render(): string
    {
        $format = (string) $this->arguments['format'];
        if (!in_array($format, self::FORMATS, true)) {
            throw new InvalidArgumentException('Unknown palette export format: '.$format, 8274619305);
        }

        $palette = $this->collectPalette($this->arguments['colors']);
        if ([] === $palette) {
            return '';
        }

        return match ($format) {
            'scss' => $this->variables('$', $palette),
            'less' => $this->variables('@', $palette),
            default => json_encode(
                ['color' => array_map(static fn (string $hex): array => ['$type' => 'color', '$value' => $hex], $palette)],
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR,
            )."\n",
        };
    }

    /**
     * @return array<string, string> HEX value by variable name
     */
    private function collectPalette(mixed $colors): array
    {
        $palette = [];
        foreach (is_array($colors) ? $colors : [] as $color) {
            $hex = (string) ($color['data']['color'] ?? '');
            $name = (string) preg_replace('/^[$@]/', '', (string) ($color['data']['variable'] ?? ''));

            if (1 === preg_match('/^#?[0-9a-fA-F]{6}\z/', $hex) && 1 === preg_match('/^[A-Za-z_][A-Za-z0-9_-]*\z/', $name)) {
                $palette[$name] = '#'.strtoupper(ltrim($hex, '#'));
            }
        }

        return $palette;
    }

    /**
     * @param array<string, string> $palette
     */
    private function variables(string $prefix, array $palette): string
    {
        $lines = '';
        foreach ($palette as $name => $hex) {
            $lines .= $prefix.$name.': '.$hex.";\n";
        }

        return $lines;
    }
}
