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

use MoveElevator\Styleguide\Utility\ElementFieldCollector;
use PHPUnit\Framework\TestCase;

use function strtoupper;

/**
 * ElementFieldCollectorTest.
 *
 * @author Konrad Michalik <km@move-elevator.de>
 * @license GPL-2.0-or-later
 */
final class ElementFieldCollectorTest extends TestCase
{
    private ElementFieldCollector $subject;

    protected function setUp(): void
    {
        $this->subject = new ElementFieldCollector();
    }

    public function testCollectReturnsPlainFieldsInOrderWithTranslatedLabels(): void
    {
        $tca = [
            'types' => ['demo' => ['showitem' => 'headline, teaser']],
            'columns' => [
                'headline' => ['label' => 'label.headline', 'config' => ['type' => 'input']],
                'teaser' => ['label' => 'label.teaser', 'config' => ['type' => 'text']],
            ],
        ];

        $fields = $this->subject->collect('demo', $tca, static fn (string $key): string => strtoupper($key));

        $this->assertSame(['LABEL.HEADLINE', 'LABEL.TEASER'], array_column($fields, 'name'));
    }

    public function testCollectSkipsTabsAndStandardPalettes(): void
    {
        $tca = [
            'types' => ['demo' => ['showitem' => '--div--;General, --palette--;;general, --palette--;;header, headline, --div--;Access, --palette--;;hidden, --palette--;;access']],
            'palettes' => [
                'general' => ['showitem' => 'CType'],
                'header' => ['showitem' => 'header'],
                'hidden' => ['showitem' => 'hidden'],
                'access' => ['showitem' => 'starttime'],
            ],
            'columns' => [
                'headline' => ['label' => 'Headline', 'config' => ['type' => 'input']],
                'CType' => ['label' => 'Type', 'config' => ['type' => 'select']],
                'header' => ['label' => 'Header', 'config' => ['type' => 'input']],
                'hidden' => ['label' => 'Hidden', 'config' => ['type' => 'check']],
                'starttime' => ['label' => 'Start', 'config' => ['type' => 'datetime']],
            ],
        ];

        $fields = $this->subject->collect('demo', $tca, static fn (string $key): string => $key);

        $this->assertSame(['Headline'], array_column($fields, 'name'));
    }

    public function testCollectSkipsFieldsOfStandardTabs(): void
    {
        $core = 'LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:';
        $tca = [
            'types' => ['demo' => ['showitem' => '--div--;'.$core.'general, headline,'
                .'--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance, space_before,'
                .'--div--;'.$core.'language, sys_language_uid,'
                .'--div--;'.$core.'access, starttime,'
                .'--div--;'.$core.'categories, categories,'
                .'--div--;'.$core.'notes, rowDescription']],
            'columns' => [
                'headline' => ['label' => 'Headline', 'config' => ['type' => 'input']],
                'space_before' => ['label' => 'Space before', 'config' => ['type' => 'select']],
                'sys_language_uid' => ['label' => 'Language', 'config' => ['type' => 'language']],
                'starttime' => ['label' => 'Start', 'config' => ['type' => 'datetime']],
                'categories' => ['label' => 'Categories', 'config' => ['type' => 'category']],
                'rowDescription' => ['label' => 'Description', 'config' => ['type' => 'text']],
            ],
        ];

        $fields = $this->subject->collect('demo', $tca, static fn (string $key): string => $key);

        $this->assertSame(['Headline'], array_column($fields, 'name'));
    }

    public function testCollectKeepsFieldsOfCustomTabs(): void
    {
        $tca = [
            'types' => ['demo' => ['showitem' => '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general, headline,'
                .'--div--;LLL:EXT:site/Resources/Private/Language/locallang.xlf:tab.settings, setting']],
            'columns' => [
                'headline' => ['label' => 'Headline', 'config' => ['type' => 'input']],
                'setting' => ['label' => 'Setting', 'config' => ['type' => 'check']],
            ],
        ];

        $fields = $this->subject->collect('demo', $tca, static fn (string $key): string => $key);

        $this->assertSame(['Headline', 'Setting'], array_column($fields, 'name'));
    }

    public function testCollectExpandsCustomPalettes(): void
    {
        $tca = [
            'types' => ['demo' => ['showitem' => '--palette--;;link']],
            'palettes' => ['link' => ['showitem' => 'url, --linebreak--, target']],
            'columns' => [
                'url' => ['label' => 'URL', 'config' => ['type' => 'input']],
                'target' => ['label' => 'Target', 'config' => ['type' => 'select']],
            ],
        ];

        $fields = $this->subject->collect('demo', $tca, static fn (string $key): string => $key);

        $this->assertSame(['URL', 'Target'], array_column($fields, 'name'));
    }

    public function testCollectDetectsRequiredFields(): void
    {
        $tca = [
            'types' => ['demo' => ['showitem' => 'a, b, c']],
            'columns' => [
                'a' => ['label' => 'A', 'config' => ['type' => 'input', 'required' => true]],
                'b' => ['label' => 'B', 'config' => ['type' => 'input', 'eval' => 'trim,required']],
                'c' => ['label' => 'C', 'config' => ['type' => 'input', 'eval' => 'trim']],
            ],
        ];

        $fields = $this->subject->collect('demo', $tca, static fn (string $key): string => $key);

        $this->assertSame([true, true, false], array_column($fields, 'required'));
    }

    public function testCollectPrefersColumnsOverridesOfTheType(): void
    {
        $tca = [
            'types' => ['demo' => [
                'showitem' => 'bodytext',
                'columnsOverrides' => ['bodytext' => [
                    'label' => 'Override label',
                    'description' => 'Override note',
                    'config' => ['required' => true],
                ]],
            ]],
            'columns' => [
                'bodytext' => ['label' => 'Base label', 'description' => 'Base note', 'config' => ['type' => 'text']],
            ],
        ];

        $fields = $this->subject->collect('demo', $tca, static fn (string $key): string => $key);

        $this->assertSame([['name' => 'Override label', 'required' => true, 'note' => 'Override note']], $fields);
    }

    public function testCollectUsesLabelOfTheShowitemEntry(): void
    {
        $tca = [
            'types' => ['demo' => ['showitem' => 'bodytext;Custom label']],
            'columns' => ['bodytext' => ['label' => 'Base label', 'config' => ['type' => 'text']]],
        ];

        $fields = $this->subject->collect('demo', $tca, static fn (string $key): string => $key);

        $this->assertSame('Custom label', $fields[0]['name']);
    }

    public function testCollectFallsBackToTheFieldNameWithoutLabel(): void
    {
        $tca = [
            'types' => ['demo' => ['showitem' => 'tx_custom']],
            'columns' => ['tx_custom' => ['config' => ['type' => 'input']]],
        ];

        $fields = $this->subject->collect('demo', $tca, static fn (string $key): string => $key);

        $this->assertSame([['name' => 'tx_custom', 'required' => false, 'note' => '']], $fields);
    }

    public function testCollectIgnoresFieldsWithoutColumnAndPassthroughFields(): void
    {
        $tca = [
            'types' => ['demo' => ['showitem' => 'missing, hidden_ref, visible']],
            'columns' => [
                'hidden_ref' => ['label' => 'Ref', 'config' => ['type' => 'passthrough']],
                'visible' => ['label' => 'Visible', 'config' => ['type' => 'input']],
            ],
        ];

        $fields = $this->subject->collect('demo', $tca, static fn (string $key): string => $key);

        $this->assertSame(['Visible'], array_column($fields, 'name'));
    }

    public function testCollectReturnsNothingForAnUnknownType(): void
    {
        $this->assertSame([], $this->subject->collect('unknown', ['types' => []], static fn (string $key): string => $key));
    }

    public function testCollectDoesNotListAFieldTwice(): void
    {
        $tca = [
            'types' => ['demo' => ['showitem' => 'headline, --palette--;;twice, headline']],
            'palettes' => ['twice' => ['showitem' => 'headline']],
            'columns' => ['headline' => ['label' => 'Headline', 'config' => ['type' => 'input']]],
        ];

        $fields = $this->subject->collect('demo', $tca, static fn (string $key): string => $key);

        $this->assertCount(1, $fields);
    }
}
