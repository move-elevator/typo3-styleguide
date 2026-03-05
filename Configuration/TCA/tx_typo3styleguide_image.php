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
        'title' => 'LLL:EXT:typo3_styleguide/Resources/Private/Language/locallang.xlf:tx_typo3styleguide_image',
        'label' => 'path',
        'label_alt' => 'caption',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'sortby' => 'sorting',
        'iconfile' => 'EXT:core/Resources/Public/Icons/T3Icons/svgs/content/content-image.svg',
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
        'path' => [
            'label' => 'LLL:EXT:typo3_styleguide/Resources/Private/Language/locallang.xlf:tx_typo3styleguide_image.path',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'max' => 255,
                'eval' => 'trim',
                'required' => true,
                'placeholder' => 'EXT:sitepackage/Resources/Public/Images/logo.svg',
            ],
        ],
        'caption' => [
            'label' => 'LLL:EXT:typo3_styleguide/Resources/Private/Language/locallang.xlf:tx_typo3styleguide_image.caption',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'max' => 255,
                'eval' => 'trim',
            ],
        ],
    ],
    'types' => [
        '0' => [
            'showitem' => 'path,caption',
        ],
    ],
];
