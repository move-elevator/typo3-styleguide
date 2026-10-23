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

return [
    'ctrl' => [
        'title' => 'LLL:EXT:typo3_styleguide/Resources/Private/Language/locallang.xlf:tx_typo3styleguide_font',
        'label' => 'font',
        'label_alt' => 'label',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'sortby' => 'sorting',
        'versioningWS' => true,
        'iconfile' => 'EXT:core/Resources/Public/Icons/T3Icons/svgs/content/content-text.svg',
        'hideTable' => true,
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'security' => [
            'ignorePageTypeRestriction' => true,
        ],
    ],
    'columns' => [
        'parentid' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'parenttable' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'font' => [
            'label' => 'LLL:EXT:typo3_styleguide/Resources/Private/Language/locallang.xlf:tx_typo3styleguide_font.font',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
                'eval' => 'trim',
                'required' => true,
                'placeholder' => 'Gabarito',
            ],
        ],
        'font_weight' => [
            'label' => 'LLL:EXT:typo3_styleguide/Resources/Private/Language/locallang.xlf:tx_typo3styleguide_font.font_weight',
            'config' => [
                'type' => 'input',
                'size' => 20,
                'max' => 100,
                'eval' => 'trim',
                'placeholder' => '400, 700',
            ],
        ],
        'fallback' => [
            'label' => 'LLL:EXT:typo3_styleguide/Resources/Private/Language/locallang.xlf:tx_typo3styleguide_font.fallback',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
                'eval' => 'trim',
                'placeholder' => 'system-ui, sans-serif',
            ],
        ],
        'label' => [
            'label' => 'LLL:EXT:typo3_styleguide/Resources/Private/Language/locallang.xlf:tx_typo3styleguide_font.label',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
                'eval' => 'trim',
            ],
        ],
        'source' => [
            'label' => 'LLL:EXT:typo3_styleguide/Resources/Private/Language/locallang.xlf:tx_typo3styleguide_font.source',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
                'eval' => 'trim',
                'placeholder' => 'Self-hosted (WOFF2)',
            ],
        ],
        'sample' => [
            'label' => 'LLL:EXT:typo3_styleguide/Resources/Private/Language/locallang.xlf:tx_typo3styleguide_font.sample',
            'config' => [
                'type' => 'text',
                'rows' => 3,
                'cols' => 40,
            ],
        ],
    ],
    'types' => [
        '0' => [
            'showitem' => 'font,font_weight,fallback,label,source,sample',
        ],
    ],
];
