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

namespace MoveElevator\Styleguide\UserFunc;

/**
 * CTypeItemsProcFunc.
 *
 * @author Konrad Michalik <km@move-elevator.de>
 * @license GPL-2.0-or-later
 */
class CTypeItemsProcFunc
{
    /**
     * @param array<string, mixed> $params
     */
    public function getItems(array &$params): void
    {
        $ctypeItems = $GLOBALS['TCA']['tt_content']['columns']['CType']['config']['items'] ?? [];

        foreach ($ctypeItems as $item) {
            $label = (string) ($item['label'] ?? '');
            $value = (string) ($item['value'] ?? '');

            if ('' === $value || '--div--' === $value) {
                continue;
            }

            $params['items'][] = [
                'label' => $label,
                'value' => $value,
                'icon' => (string) ($item['icon'] ?? ''),
            ];
        }
    }
}
