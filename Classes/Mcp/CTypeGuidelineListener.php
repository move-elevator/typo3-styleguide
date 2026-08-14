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

namespace MoveElevator\Styleguide\Mcp;

use Hn\McpServer\Event\AfterSchemaLoadEvent;
use TYPO3\CMS\Core\Attribute\AsEventListener;

use function is_array;
use function is_string;

/**
 * CTypeGuidelineListener.
 *
 * @author Konrad Michalik <km@move-elevator.de>
 * @license GPL-2.0-or-later
 */
#[AsEventListener(identifier: 'typo3-styleguide/ctype-guideline-schema')]
final class CTypeGuidelineListener
{
    private const GUIDELINE_FIELD = '_guideline';

    public function __invoke(AfterSchemaLoadEvent $event): void
    {
        if ('tt_content' !== $event->getTable() || $event->hasField(self::GUIDELINE_FIELD)) {
            return;
        }

        $cType = $event->getType();
        if ('' === $cType) {
            return;
        }

        $description = $this->resolveItemDescription($cType);
        if (null === $description) {
            return;
        }

        // The MCP server evaluates field descriptions and type labels, but never
        // CType item descriptions — so the "what is this element for" hint never
        // reaches the LLM. The event API is field-oriented, hence the pseudo field.
        $event->addField(self::GUIDELINE_FIELD, [
            'label' => 'Editorial guideline',
            'type' => 'none',
            'description' => $description,
            'mcp' => ['computed' => true],
        ]);
    }

    private function resolveItemDescription(string $cType): ?string
    {
        $items = $GLOBALS['TCA']['tt_content']['columns']['CType']['config']['items'] ?? null;
        if (!is_array($items)) {
            return null;
        }

        foreach ($items as $item) {
            if (!is_array($item) || ($item['value'] ?? null) !== $cType) {
                continue;
            }

            $description = $item['description'] ?? null;

            return is_string($description) && '' !== $description ? $description : null;
        }

        return null;
    }
}
