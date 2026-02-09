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
use TYPO3\CMS\Core\Domain\Repository\PageRepository;
use TYPO3\CMS\Core\Utility\{ArrayUtility, ExtensionManagementUtility};

defined('TYPO3') || exit('Access denied.');

ExtensionManagementUtility::addTcaSelectItem(
    'pages',
    'doktype',
    [
        'LLL:EXT:'.Configuration::EXT_KEY.'/Resources/Private/Language/locallang.xlf:page.styleguide',
        Configuration::PAGE_TYPE,
        'apps-pagetree-page-styleguide',
    ],
    '1',
    'after',
);

ArrayUtility::mergeRecursiveWithOverrule(
    $GLOBALS['TCA']['pages'],
    [
        'ctrl' => [
            'typeicon_classes' => [
                Configuration::PAGE_TYPE => 'apps-pagetree-page-styleguide',
                Configuration::PAGE_TYPE.'-contentFromPid' => 'apps-pagetree-page-styleguide',
                Configuration::PAGE_TYPE.'-root' => 'apps-pagetree-page-styleguide',
                Configuration::PAGE_TYPE.'-hideinmenu' => 'apps-pagetree-page-styleguide-hideinmenu',
            ],
        ],
        'types' => [
            Configuration::PAGE_TYPE => [
                'showitem' => $GLOBALS['TCA']['pages']['types'][PageRepository::DOKTYPE_DEFAULT]['showitem'],
            ],
        ],
    ],
);
