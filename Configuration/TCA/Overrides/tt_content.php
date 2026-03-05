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

use MoveElevator\Styleguide\Configuration;
use MoveElevator\Styleguide\Preview\StyleguidePreviewRenderer;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || exit('Access denied.');

$lll = 'LLL:EXT:'.Configuration::EXT_KEY.'/Resources/Private/Language/locallang.xlf:';

// --- Technical Headline ---
ExtensionManagementUtility::addTcaSelectItem(
    'tt_content',
    'CType',
    [
        'label' => $lll.'contentelement.technical_headline.label',
        'value' => 'metypo3styleguide_technicalheadline',
        'icon' => 'content-info',
        'description' => $lll.'contentelement.technical_headline.description',
    ],
    'html',
    'after',
);

// --- Colors ---
ExtensionManagementUtility::addTcaSelectItem(
    'tt_content',
    'CType',
    [
        'label' => $lll.'contentelement.colors.label',
        'value' => 'metypo3styleguide_colors',
        'icon' => 'content-info',
        'description' => $lll.'contentelement.colors.description',
    ],
    'metypo3styleguide_technicalheadline',
    'after',
);

// --- Fonts ---
ExtensionManagementUtility::addTcaSelectItem(
    'tt_content',
    'CType',
    [
        'label' => $lll.'contentelement.fonts.label',
        'value' => 'metypo3styleguide_fonts',
        'icon' => 'content-info',
        'description' => $lll.'contentelement.fonts.description',
    ],
    'metypo3styleguide_colors',
    'after',
);

// --- Icons ---
ExtensionManagementUtility::addTcaSelectItem(
    'tt_content',
    'CType',
    [
        'label' => $lll.'contentelement.icons.label',
        'value' => 'metypo3styleguide_icons',
        'icon' => 'content-info',
        'description' => $lll.'contentelement.icons.description',
    ],
    'metypo3styleguide_fonts',
    'after',
);

// --- Images ---
ExtensionManagementUtility::addTcaSelectItem(
    'tt_content',
    'CType',
    [
        'label' => $lll.'contentelement.images.label',
        'value' => 'metypo3styleguide_images',
        'icon' => 'content-info',
        'description' => $lll.'contentelement.images.description',
    ],
    'metypo3styleguide_icons',
    'after',
);

$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['metypo3styleguide_technicalheadline'] = 'content-info';
$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['metypo3styleguide_colors'] = 'content-info';
$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['metypo3styleguide_fonts'] = 'content-info';
$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['metypo3styleguide_icons'] = 'content-info';
$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['metypo3styleguide_images'] = 'content-info';

$GLOBALS['TCA']['tt_content']['columns'] = array_replace_recursive(
    $GLOBALS['TCA']['tt_content']['columns'],
    [
        'tx_metypo3styleguide_technicalheadlinetag' => [
            'label' => $lll.'contentelement.technical_headline.tag',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 'h2',
                'items' => [
                    [
                        'label' => $lll.'contentelement.technical_headline.tag.h2',
                        'value' => 'h2',
                    ],
                    [
                        'label' => $lll.'contentelement.technical_headline.tag.h3',
                        'value' => 'h3',
                    ],
                    [
                        'label' => $lll.'contentelement.technical_headline.tag.h4',
                        'value' => 'h4',
                    ],
                ],
            ],
        ],
        'tx_metypo3styleguide_colors' => [
            'label' => $lll.'contentelement.colors.items',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_metypo3styleguide_color',
                'foreign_field' => 'parentid',
                'foreign_table_field' => 'parenttable',
                'foreign_sortby' => 'sorting',
                'appearance' => [
                    'collapseAll' => true,
                    'expandSingle' => true,
                    'useSortable' => true,
                    'enabledControls' => [
                        'dragdrop' => true,
                    ],
                ],
            ],
        ],
        'tx_metypo3styleguide_fonts' => [
            'label' => $lll.'contentelement.fonts.items',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_metypo3styleguide_font',
                'foreign_field' => 'parentid',
                'foreign_table_field' => 'parenttable',
                'foreign_sortby' => 'sorting',
                'appearance' => [
                    'collapseAll' => true,
                    'expandSingle' => true,
                    'useSortable' => true,
                    'enabledControls' => [
                        'dragdrop' => true,
                    ],
                ],
            ],
        ],
        'tx_metypo3styleguide_icons_path' => [
            'label' => $lll.'contentelement.icons.path',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'max' => 255,
                'eval' => 'trim',
                'required' => true,
                'placeholder' => 'EXT:sitepackage/Resources/Public/Icons/',
            ],
        ],
        'tx_metypo3styleguide_images' => [
            'label' => $lll.'contentelement.images.items',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_metypo3styleguide_image',
                'foreign_field' => 'parentid',
                'foreign_table_field' => 'parenttable',
                'foreign_sortby' => 'sorting',
                'appearance' => [
                    'collapseAll' => true,
                    'expandSingle' => true,
                    'useSortable' => true,
                    'enabledControls' => [
                        'dragdrop' => true,
                    ],
                ],
            ],
        ],
    ],
);

$GLOBALS['TCA']['tt_content']['types']['metypo3styleguide_technicalheadline'] = [
    'previewRenderer' => StyleguidePreviewRenderer::class,
    'showitem' => '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    --palette--;;general,
                    --palette--;;header,tx_metypo3styleguide_technicalheadlinetag,subheader,bodytext,
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
                    --palette--;;frames,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                    --palette--;;language,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    --palette--;;hidden,
                    --palette--;;access,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
                    categories,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
                    rowDescription,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,',
    'columnsOverrides' => [
        'bodytext' => [
            'config' => [
                'enableRichtext' => true,
                'richtextConfiguration' => 'default',
            ],
        ],
    ],
];

$GLOBALS['TCA']['tt_content']['types']['metypo3styleguide_colors'] = [
    'previewRenderer' => StyleguidePreviewRenderer::class,
    'showitem' => '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    --palette--;;general,
                    --palette--;;header,tx_metypo3styleguide_colors,
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
                    --palette--;;frames,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                    --palette--;;language,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    --palette--;;hidden,
                    --palette--;;access,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
                    categories,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
                    rowDescription,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,',
];

$GLOBALS['TCA']['tt_content']['types']['metypo3styleguide_fonts'] = [
    'previewRenderer' => StyleguidePreviewRenderer::class,
    'showitem' => '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    --palette--;;general,
                    --palette--;;header,tx_metypo3styleguide_fonts,
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
                    --palette--;;frames,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                    --palette--;;language,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    --palette--;;hidden,
                    --palette--;;access,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
                    categories,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
                    rowDescription,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,',
];

$GLOBALS['TCA']['tt_content']['types']['metypo3styleguide_icons'] = [
    'previewRenderer' => StyleguidePreviewRenderer::class,
    'showitem' => '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    --palette--;;general,
                    --palette--;;header,tx_metypo3styleguide_icons_path,
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
                    --palette--;;frames,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                    --palette--;;language,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    --palette--;;hidden,
                    --palette--;;access,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
                    categories,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
                    rowDescription,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,',
];

$GLOBALS['TCA']['tt_content']['types']['metypo3styleguide_images'] = [
    'previewRenderer' => StyleguidePreviewRenderer::class,
    'showitem' => '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    --palette--;;general,
                    --palette--;;header,tx_metypo3styleguide_images,
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
                    --palette--;;frames,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                    --palette--;;language,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    --palette--;;hidden,
                    --palette--;;access,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
                    categories,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
                    rowDescription,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,',
];
