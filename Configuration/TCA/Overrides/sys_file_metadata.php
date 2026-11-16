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
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || exit('Access denied.');

$lll = 'LLL:EXT:'.Configuration::EXT_KEY.'/Resources/Private/Language/locallang.xlf:';

ExtensionManagementUtility::addTCAcolumns('sys_file_metadata', [
    'tx_typo3styleguide_copyright' => [
        'label' => $lll.'metadata.copyright',
        'config' => [
            'type' => 'input',
            'size' => 30,
            'max' => 255,
        ],
    ],
    'tx_typo3styleguide_usage_rights' => [
        'label' => $lll.'metadata.usage_rights',
        'config' => [
            'type' => 'text',
            'cols' => 40,
            'rows' => 3,
        ],
    ],
    'tx_typo3styleguide_license_until' => [
        'label' => $lll.'metadata.license_until',
        'config' => [
            'type' => 'datetime',
            'format' => 'date',
            'nullable' => true,
        ],
    ],
    'tx_typo3styleguide_source' => [
        'label' => $lll.'metadata.source',
        'config' => [
            'type' => 'input',
            'size' => 30,
            'max' => 255,
        ],
    ],
]);

ExtensionManagementUtility::addToAllTCAtypes(
    'sys_file_metadata',
    '--div--;'.$lll.'metadata.tab,tx_typo3styleguide_copyright,tx_typo3styleguide_usage_rights,tx_typo3styleguide_license_until,tx_typo3styleguide_source',
);
