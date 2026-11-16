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
        'title' => 'LLL:EXT:typo3_styleguide/Resources/Private/Language/locallang.xlf:tx_typo3styleguide_color',
        'label' => 'label',
        'label_alt' => 'color',
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
        'color' => [
            'label' => 'LLL:EXT:typo3_styleguide/Resources/Private/Language/locallang.xlf:tx_typo3styleguide_color.color',
            'config' => [
                'type' => 'input',
                'size' => 10,
                'max' => 7,
                'eval' => 'trim',
                'required' => true,
                'placeholder' => '#000000',
            ],
        ],
        'label' => [
            'label' => 'LLL:EXT:typo3_styleguide/Resources/Private/Language/locallang.xlf:tx_typo3styleguide_color.label',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
                'eval' => 'trim',
            ],
        ],
        'cmyk' => [
            'label' => 'LLL:EXT:typo3_styleguide/Resources/Private/Language/locallang.xlf:tx_typo3styleguide_color.cmyk',
            'description' => 'LLL:EXT:typo3_styleguide/Resources/Private/Language/locallang.xlf:tx_typo3styleguide_color.detail.description',
            'config' => [
                'type' => 'input',
                'size' => 20,
                'max' => 50,
                'eval' => 'trim',
                'placeholder' => '0, 72, 100, 0',
            ],
        ],
        'pms' => [
            'label' => 'LLL:EXT:typo3_styleguide/Resources/Private/Language/locallang.xlf:tx_typo3styleguide_color.pms',
            'description' => 'LLL:EXT:typo3_styleguide/Resources/Private/Language/locallang.xlf:tx_typo3styleguide_color.detail.description',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 100,
                'eval' => 'trim',
                'placeholder' => 'PANTONE 165 C',
            ],
        ],
        'variable' => [
            'label' => 'LLL:EXT:typo3_styleguide/Resources/Private/Language/locallang.xlf:tx_typo3styleguide_color.variable',
            'description' => 'LLL:EXT:typo3_styleguide/Resources/Private/Language/locallang.xlf:tx_typo3styleguide_color.detail.description',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 100,
                'eval' => 'trim',
                'placeholder' => '$color-primary',
            ],
        ],
        'role' => [
            'label' => 'LLL:EXT:typo3_styleguide/Resources/Private/Language/locallang.xlf:tx_typo3styleguide_color.role',
            'description' => 'LLL:EXT:typo3_styleguide/Resources/Private/Language/locallang.xlf:tx_typo3styleguide_color.role.description',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => '',
                'items' => [
                    [
                        'label' => 'LLL:EXT:typo3_styleguide/Resources/Private/Language/locallang.xlf:tx_typo3styleguide_color.role.none',
                        'value' => '',
                    ],
                    [
                        'label' => 'LLL:EXT:typo3_styleguide/Resources/Private/Language/locallang.xlf:tx_typo3styleguide_color.role.success',
                        'value' => 'success',
                    ],
                    [
                        'label' => 'LLL:EXT:typo3_styleguide/Resources/Private/Language/locallang.xlf:tx_typo3styleguide_color.role.warning',
                        'value' => 'warning',
                    ],
                    [
                        'label' => 'LLL:EXT:typo3_styleguide/Resources/Private/Language/locallang.xlf:tx_typo3styleguide_color.role.error',
                        'value' => 'error',
                    ],
                ],
            ],
        ],
    ],
    'types' => [
        '0' => [
            'showitem' => 'color,label,cmyk,pms,variable,role',
        ],
    ],
];
