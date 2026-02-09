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

use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;

return [
    'apps-pagetree-page-styleguide' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:typo3_styleguide/Resources/Public/Icons/page-styleguide.svg',
    ],
    'apps-pagetree-page-styleguide-hideinmenu' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:typo3_styleguide/Resources/Public/Icons/page-styleguide-hidden.svg',
    ],
];
