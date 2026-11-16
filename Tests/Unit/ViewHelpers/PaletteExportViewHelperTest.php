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
use MoveElevator\Styleguide\ViewHelpers\PaletteExportViewHelper;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function json_decode;

use const JSON_THROW_ON_ERROR;

/**
 * PaletteExportViewHelperTest.
 *
 * @author Konrad Michalik <km@move-elevator.de>
 * @license GPL-2.0-or-later
 */
final class PaletteExportViewHelperTest extends TestCase
{
    public function testScssExportsOneVariablePerColorAndNormalizesNames(): void
    {
        $this->assertSame(
            "\$color-orange-01: #FF6418;\n\$color-blue-01: #003241;\n",
            $this->export([
                $this->row('#ff6418', '$color-orange-01'),
                $this->row('003241', 'color-blue-01'),
            ], 'scss'),
        );
    }

    public function testLessUsesTheAtSign(): void
    {
        $this->assertSame(
            "@color-orange-01: #FF6418;\n",
            $this->export([$this->row('#FF6418', '$color-orange-01')], 'less'),
        );
    }

    public function testJsonFollowsTheDesignTokensFormat(): void
    {
        $json = $this->export([$this->row('#ff6418', '@color-orange-01')], 'json');

        $this->assertSame(
            ['color' => ['color-orange-01' => ['$type' => 'color', '$value' => '#FF6418']]],
            json_decode($json, true, 512, JSON_THROW_ON_ERROR),
        );
    }

    public function testColorsWithoutVariableNameOrWithInvalidValuesAreSkipped(): void
    {
        $this->assertSame(
            "\$kept: #003241;\n",
            $this->export([
                $this->row('#FF6418', ''),
                $this->row('orange', '$invalid-color'),
                $this->row('#123456', 'bad name; injected'),
                $this->row('#003241', 'kept'),
            ], 'scss'),
        );
    }

    #[DataProvider('invalidNameProvider')]
    public function testInvalidVariableNamesAreSkipped(string $name): void
    {
        $this->assertSame('', $this->export([$this->row('#FF6418', $name)], 'scss'));
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function invalidNameProvider(): iterable
    {
        yield 'trailing newline' => ["foo\n"];
        yield 'starts with a digit' => ['1abc'];
        yield 'only digits' => ['0'];
        yield 'doubled dollar sign' => ['$$color'];
        yield 'dollar sign followed by at sign' => ['$@color'];
    }

    public function testNothingToExportReturnsAnEmptyString(): void
    {
        $this->assertSame('', $this->export([$this->row('#FF6418', '')], 'scss'));
        $this->assertSame('', $this->export([], 'json'));
    }

    public function testUnknownFormatIsRejected(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->export([$this->row('#FF6418', 'a')], 'ase');
    }

    /**
     * @param list<array{data: array{color: string, variable: string}}> $colors
     */
    private function export(array $colors, string $format): string
    {
        $viewHelper = new PaletteExportViewHelper();
        $viewHelper->setArguments(['colors' => $colors, 'format' => $format]);

        return $viewHelper->render();
    }

    /**
     * @return array{data: array{color: string, variable: string}}
     */
    private function row(string $color, string $variable): array
    {
        return ['data' => ['color' => $color, 'variable' => $variable]];
    }
}
