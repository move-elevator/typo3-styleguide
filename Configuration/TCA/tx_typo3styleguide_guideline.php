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
        'title' => 'LLL:EXT:typo3_styleguide/Resources/Private/Language/locallang.xlf:tx_typo3styleguide_guideline',
        'label' => 'text',
        'label_alt' => 'type',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'sortby' => 'sorting',
        'versioningWS' => true,
        'iconfile' => 'EXT:typo3_styleguide/Resources/Public/Icons/content-styleguide-guidelines.svg',
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
        'type' => [
            'label' => 'LLL:EXT:typo3_styleguide/Resources/Private/Language/locallang.xlf:tx_typo3styleguide_guideline.type',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 'do',
                'items' => [
                    [
                        'label' => 'LLL:EXT:typo3_styleguide/Resources/Private/Language/locallang.xlf:tx_typo3styleguide_guideline.type.do',
                        'value' => 'do',
                    ],
                    [
                        'label' => 'LLL:EXT:typo3_styleguide/Resources/Private/Language/locallang.xlf:tx_typo3styleguide_guideline.type.dont',
                        'value' => 'dont',
                    ],
                    [
                        'label' => 'LLL:EXT:typo3_styleguide/Resources/Private/Language/locallang.xlf:tx_typo3styleguide_guideline.type.note',
                        'value' => 'note',
                    ],
                ],
            ],
        ],
        'title' => [
            'label' => 'LLL:EXT:typo3_styleguide/Resources/Private/Language/locallang.xlf:tx_typo3styleguide_guideline.title',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'max' => 255,
                'eval' => 'trim',
            ],
        ],
        'text' => [
            'label' => 'LLL:EXT:typo3_styleguide/Resources/Private/Language/locallang.xlf:tx_typo3styleguide_guideline.text',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'cols' => 50,
                'rows' => 3,
                'eval' => 'trim',
                'required' => true,
            ],
        ],
        'image' => [
            'label' => 'LLL:EXT:typo3_styleguide/Resources/Private/Language/locallang.xlf:tx_typo3styleguide_guideline.image',
            'description' => 'LLL:EXT:typo3_styleguide/Resources/Private/Language/locallang.xlf:tx_typo3styleguide_guideline.image.description',
            'config' => [
                'type' => 'file',
                'maxitems' => 1,
                'allowed' => 'common-image-types',
            ],
        ],
        'path' => [
            'label' => 'LLL:EXT:typo3_styleguide/Resources/Private/Language/locallang.xlf:tx_typo3styleguide_guideline.path',
            'description' => 'LLL:EXT:typo3_styleguide/Resources/Private/Language/locallang.xlf:tx_typo3styleguide_guideline.path.description',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'max' => 255,
                'eval' => 'trim',
                'placeholder' => 'EXT:sitepackage/Resources/Public/Images/example.svg',
            ],
        ],
    ],
    'types' => [
        '0' => [
            'showitem' => 'type,title,text,image,path',
        ],
    ],
];
