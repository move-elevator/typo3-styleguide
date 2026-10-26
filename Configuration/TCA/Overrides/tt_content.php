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

ExtensionManagementUtility::addTcaSelectItemGroup(
    'tt_content',
    'CType',
    'styleguide',
    $lll.'wizard.tab.styleguide',
);

// --- Technical Headline ---
ExtensionManagementUtility::addTcaSelectItem(
    'tt_content',
    'CType',
    [
        'label' => $lll.'contentelement.technical_headline.label',
        'value' => 'typo3styleguide_technicalheadline',
        'icon' => 'content-styleguide-headline',
        'description' => $lll.'contentelement.technical_headline.description',
        'group' => 'styleguide',
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
        'value' => 'typo3styleguide_colors',
        'icon' => 'content-styleguide-colors',
        'description' => $lll.'contentelement.colors.description',
        'group' => 'styleguide',
    ],
    'typo3styleguide_technicalheadline',
    'after',
);

// --- Fonts ---
ExtensionManagementUtility::addTcaSelectItem(
    'tt_content',
    'CType',
    [
        'label' => $lll.'contentelement.fonts.label',
        'value' => 'typo3styleguide_fonts',
        'icon' => 'content-styleguide-fonts',
        'description' => $lll.'contentelement.fonts.description',
        'group' => 'styleguide',
    ],
    'typo3styleguide_colors',
    'after',
);

// --- Icons ---
ExtensionManagementUtility::addTcaSelectItem(
    'tt_content',
    'CType',
    [
        'label' => $lll.'contentelement.icons.label',
        'value' => 'typo3styleguide_icons',
        'icon' => 'content-styleguide-icons',
        'description' => $lll.'contentelement.icons.description',
        'group' => 'styleguide',
    ],
    'typo3styleguide_fonts',
    'after',
);

// --- Images ---
ExtensionManagementUtility::addTcaSelectItem(
    'tt_content',
    'CType',
    [
        'label' => $lll.'contentelement.images.label',
        'value' => 'typo3styleguide_images',
        'icon' => 'content-styleguide-images',
        'description' => $lll.'contentelement.images.description',
        'group' => 'styleguide',
    ],
    'typo3styleguide_icons',
    'after',
);

// --- Table of Contents ---
ExtensionManagementUtility::addTcaSelectItem(
    'tt_content',
    'CType',
    [
        'label' => $lll.'contentelement.tableofcontents.label',
        'value' => 'typo3styleguide_tableofcontents',
        'icon' => 'content-styleguide-tableofcontents',
        'description' => $lll.'contentelement.tableofcontents.description',
        'group' => 'styleguide',
    ],
    'typo3styleguide_images',
    'after',
);

// --- Gallery ---
ExtensionManagementUtility::addTcaSelectItem(
    'tt_content',
    'CType',
    [
        'label' => $lll.'contentelement.gallery.label',
        'value' => 'typo3styleguide_gallery',
        'icon' => 'content-styleguide-gallery',
        'description' => $lll.'contentelement.gallery.description',
        'group' => 'styleguide',
    ],
    'typo3styleguide_tableofcontents',
    'after',
);

// --- Guidelines ---
ExtensionManagementUtility::addTcaSelectItem(
    'tt_content',
    'CType',
    [
        'label' => $lll.'contentelement.guidelines.label',
        'value' => 'typo3styleguide_guidelines',
        'icon' => 'content-styleguide-guidelines',
        'description' => $lll.'contentelement.guidelines.description',
        'group' => 'styleguide',
    ],
    'typo3styleguide_gallery',
    'after',
);

$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['typo3styleguide_technicalheadline'] = 'content-styleguide-headline';
$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['typo3styleguide_colors'] = 'content-styleguide-colors';
$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['typo3styleguide_fonts'] = 'content-styleguide-fonts';
$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['typo3styleguide_icons'] = 'content-styleguide-icons';
$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['typo3styleguide_images'] = 'content-styleguide-images';
$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['typo3styleguide_tableofcontents'] = 'content-styleguide-tableofcontents';
$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['typo3styleguide_gallery'] = 'content-styleguide-gallery';
$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['typo3styleguide_guidelines'] = 'content-styleguide-guidelines';

$GLOBALS['TCA']['tt_content']['columns'] = array_replace_recursive(
    $GLOBALS['TCA']['tt_content']['columns'],
    [
        'tx_typo3styleguide_technicalheadlinetag' => [
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
        'tx_typo3styleguide_colors' => [
            'label' => $lll.'contentelement.colors.items',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_typo3styleguide_color',
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
        'tx_typo3styleguide_colors_layout' => [
            'label' => $lll.'contentelement.colors.layout',
            'onChange' => 'reload',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 'swatches',
                'items' => [
                    [
                        'label' => $lll.'contentelement.colors.layout.swatches',
                        'value' => 'swatches',
                    ],
                    [
                        'label' => $lll.'contentelement.colors.layout.detail',
                        'value' => 'detail',
                    ],
                    [
                        'label' => $lll.'contentelement.colors.layout.scale',
                        'value' => 'scale',
                    ],
                    [
                        'label' => $lll.'contentelement.colors.layout.status',
                        'value' => 'status',
                    ],
                ],
            ],
        ],
        'tx_typo3styleguide_colors_contrast_light' => [
            'label' => $lll.'contentelement.colors.contrast_light',
            'description' => $lll.'contentelement.colors.contrast.description',
            'displayCond' => 'FIELD:tx_typo3styleguide_colors_layout:IN:detail,status',
            'config' => [
                'type' => 'input',
                'size' => 10,
                'max' => 7,
                'eval' => 'trim',
                'default' => '#FFFFFF',
                'placeholder' => '#FFFFFF',
            ],
        ],
        'tx_typo3styleguide_colors_contrast_dark' => [
            'label' => $lll.'contentelement.colors.contrast_dark',
            'description' => $lll.'contentelement.colors.contrast.description',
            'displayCond' => 'FIELD:tx_typo3styleguide_colors_layout:IN:detail,status',
            'config' => [
                'type' => 'input',
                'size' => 10,
                'max' => 7,
                'eval' => 'trim',
                'default' => '#000000',
                'placeholder' => '#000000',
            ],
        ],
        'tx_typo3styleguide_fonts' => [
            'label' => $lll.'contentelement.fonts.items',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_typo3styleguide_font',
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
        'tx_typo3styleguide_fonts_layout' => [
            'label' => $lll.'contentelement.fonts.layout',
            'onChange' => 'reload',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 'specimen',
                'items' => [
                    [
                        'label' => $lll.'contentelement.fonts.layout.specimen',
                        'value' => 'specimen',
                    ],
                    [
                        'label' => $lll.'contentelement.fonts.layout.detail',
                        'value' => 'detail',
                    ],
                ],
            ],
        ],
        'tx_typo3styleguide_fonts_fallback' => [
            'label' => $lll.'contentelement.fonts.fallback',
            'description' => $lll.'contentelement.fonts.fallback.description',
            'displayCond' => 'FIELD:tx_typo3styleguide_fonts_layout:=:detail',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'max' => 255,
                'eval' => 'trim',
                'placeholder' => "'Segoe UI', sans-serif",
            ],
        ],
        'tx_typo3styleguide_fonts_sample' => [
            'label' => $lll.'contentelement.fonts.sample',
            'description' => $lll.'contentelement.fonts.sample.description',
            'displayCond' => 'FIELD:tx_typo3styleguide_fonts_layout:=:detail',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'max' => 255,
                'eval' => 'trim',
            ],
        ],
        'tx_typo3styleguide_fonts_download' => [
            'label' => $lll.'contentelement.fonts.download',
            'displayCond' => 'FIELD:tx_typo3styleguide_fonts_layout:=:detail',
            'config' => [
                'type' => 'link',
                'size' => 50,
                'allowedTypes' => ['file', 'url'],
            ],
        ],
        'tx_typo3styleguide_icons_path' => [
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
        'tx_typo3styleguide_icons_exclude' => [
            'label' => $lll.'contentelement.icons.exclude',
            'description' => $lll.'contentelement.icons.exclude.description',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'max' => 255,
                'eval' => 'trim',
                'placeholder' => '*-alt.svg, legacy-*, *.json',
            ],
        ],
        'tx_typo3styleguide_gallery_images' => [
            'label' => $lll.'contentelement.gallery.images',
            'config' => [
                'type' => 'file',
                'allowed' => 'common-image-types',
            ],
        ],
        'tx_typo3styleguide_gallery_columns' => [
            'label' => $lll.'contentelement.gallery.columns',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => '4',
                'items' => [
                    ['label' => '2', 'value' => '2'],
                    ['label' => '3', 'value' => '3'],
                    ['label' => '4', 'value' => '4'],
                    ['label' => '5', 'value' => '5'],
                    ['label' => '6', 'value' => '6'],
                ],
            ],
        ],
        'tx_typo3styleguide_gallery_downloads' => [
            'label' => $lll.'contentelement.gallery.downloads',
            'description' => $lll.'contentelement.gallery.downloads.description',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'default' => 0,
            ],
        ],
        'tx_typo3styleguide_guidelines' => [
            'label' => $lll.'contentelement.guidelines.items',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_typo3styleguide_guideline',
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
        'tx_typo3styleguide_images_downloads' => [
            'label' => $lll.'contentelement.images.downloads',
            'description' => $lll.'contentelement.images.downloads.description',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'default' => 0,
            ],
        ],
        'tx_typo3styleguide_images' => [
            'label' => $lll.'contentelement.images.items',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_typo3styleguide_image',
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
        'tx_typo3styleguide_tableofcontents_layout' => [
            'label' => $lll.'contentelement.tableofcontents.layout',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 'list',
                'items' => [
                    [
                        'label' => $lll.'contentelement.tableofcontents.layout.list',
                        'value' => 'list',
                    ],
                    [
                        'label' => $lll.'contentelement.tableofcontents.layout.cards',
                        'value' => 'cards',
                    ],
                ],
            ],
        ],
    ],
);

$GLOBALS['TCA']['tt_content']['types']['typo3styleguide_technicalheadline'] = [
    'previewRenderer' => StyleguidePreviewRenderer::class,
    'showitem' => '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    --palette--;;general,
                    --palette--;;header,tx_typo3styleguide_technicalheadlinetag,subheader,bodytext,
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

$GLOBALS['TCA']['tt_content']['types']['typo3styleguide_colors'] = [
    'previewRenderer' => StyleguidePreviewRenderer::class,
    'showitem' => '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    --palette--;;general,
                    --palette--;;header,tx_typo3styleguide_colors_layout,tx_typo3styleguide_colors,bodytext,
                    tx_typo3styleguide_colors_contrast_light,tx_typo3styleguide_colors_contrast_dark,
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
            'label' => $lll.'contentelement.colors.usage',
            'description' => $lll.'contentelement.colors.usage.description',
            'displayCond' => 'FIELD:tx_typo3styleguide_colors_layout:IN:detail,status',
            'config' => [
                'enableRichtext' => true,
                'richtextConfiguration' => 'default',
            ],
        ],
    ],
];

$GLOBALS['TCA']['tt_content']['types']['typo3styleguide_fonts'] = [
    'previewRenderer' => StyleguidePreviewRenderer::class,
    'showitem' => '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    --palette--;;general,
                    --palette--;;header,tx_typo3styleguide_fonts_layout,tx_typo3styleguide_fonts,
                    tx_typo3styleguide_fonts_fallback,tx_typo3styleguide_fonts_sample,tx_typo3styleguide_fonts_download,bodytext,
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
            'label' => $lll.'contentelement.fonts.note',
            'description' => $lll.'contentelement.fonts.note.description',
            'displayCond' => 'FIELD:tx_typo3styleguide_fonts_layout:=:detail',
            'config' => [
                'enableRichtext' => true,
                'richtextConfiguration' => 'default',
            ],
        ],
    ],
];

$GLOBALS['TCA']['tt_content']['types']['typo3styleguide_icons'] = [
    'previewRenderer' => StyleguidePreviewRenderer::class,
    'showitem' => '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    --palette--;;general,
                    --palette--;;header,tx_typo3styleguide_icons_path,tx_typo3styleguide_icons_exclude,
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

$GLOBALS['TCA']['tt_content']['types']['typo3styleguide_images'] = [
    'previewRenderer' => StyleguidePreviewRenderer::class,
    'showitem' => '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    --palette--;;general,
                    --palette--;;header,tx_typo3styleguide_images,tx_typo3styleguide_images_downloads,
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

$GLOBALS['TCA']['tt_content']['types']['typo3styleguide_tableofcontents'] = [
    'previewRenderer' => StyleguidePreviewRenderer::class,
    'showitem' => '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    --palette--;;general,
                    --palette--;;header,tx_typo3styleguide_tableofcontents_layout,
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

$GLOBALS['TCA']['tt_content']['types']['typo3styleguide_guidelines'] = [
    'previewRenderer' => StyleguidePreviewRenderer::class,
    'showitem' => '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    --palette--;;general,
                    --palette--;;header,tx_typo3styleguide_guidelines,
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

$GLOBALS['TCA']['tt_content']['types']['typo3styleguide_gallery'] = [
    'previewRenderer' => StyleguidePreviewRenderer::class,
    'showitem' => '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    --palette--;;general,
                    --palette--;;header,tx_typo3styleguide_gallery_images,tx_typo3styleguide_gallery_columns,tx_typo3styleguide_gallery_downloads,
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
