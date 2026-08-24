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
use MoveElevator\Styleguide\Configuration;
use TYPO3\CMS\Core\Attribute\AsEventListener;
use TYPO3\CMS\Core\Database\{Connection, ConnectionPool};
use TYPO3\CMS\Core\Utility\GeneralUtility;

use function array_map;
use function implode;
use function in_array;
use function is_array;
use function is_string;
use function sprintf;
use function str_starts_with;

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
    private const EXAMPLES_FIELD = '_examples';
    private const CTYPE_PREFIX = 'typo3styleguide_';

    /**
     * @var list<int>|null
     */
    private ?array $exampleRootPageUids = null;

    public function __invoke(AfterSchemaLoadEvent $event): void
    {
        if ('tt_content' !== $event->getTable()) {
            return;
        }

        $cType = $event->getType();
        if ('' === $cType) {
            return;
        }

        $this->addGuideline($event, $cType);
        $this->addExamplePointer($event, $cType);
    }

    private function addGuideline(AfterSchemaLoadEvent $event, string $cType): void
    {
        if ($event->hasField(self::GUIDELINE_FIELD)) {
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
            'description' => $description,
            'config' => [
                'type' => 'none',
                'readOnly' => true,
            ],
            'mcp' => ['computed' => true],
        ]);
    }

    /**
     * Points at the styleguide pages instead of describing their content. A
     * maintained example conveys conventions that prose cannot, and the pointer
     * stays valid as long as examples exist — unlike a reference to a record uid.
     */
    private function addExamplePointer(AfterSchemaLoadEvent $event, string $cType): void
    {
        if (!str_starts_with($cType, self::CTYPE_PREFIX) || $event->hasField(self::EXAMPLES_FIELD)) {
            return;
        }

        $rootPageUids = $this->getExampleRootPageUids();
        if ([] === $rootPageUids) {
            return;
        }

        $event->addField(self::EXAMPLES_FIELD, [
            'label' => 'Reference examples',
            'description' => sprintf(
                'Maintained examples exist in the styleguide page tree at pid %s. Read "tt_content" there filtered by '
                .'this CType, and pass the fields parameter to keep the output small. GetPageTree resolves the '
                .'rendered frontend URL.',
                implode(', ', $rootPageUids),
            ),
            'config' => [
                'type' => 'none',
                'readOnly' => true,
            ],
            'mcp' => ['computed' => true],
        ]);
    }

    /**
     * Only the topmost page of each styleguide tree. Subpages carry the same
     * doktype and are reachable from there, so listing them all would bloat the
     * hint and would go stale as soon as a page is added.
     *
     * @return list<int>
     */
    private function getExampleRootPageUids(): array
    {
        if (null !== $this->exampleRootPageUids) {
            return $this->exampleRootPageUids;
        }

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('pages');

        $pages = $queryBuilder
            ->select('uid', 'pid')
            ->from('pages')
            ->where(
                $queryBuilder->expr()->eq(
                    'doktype',
                    $queryBuilder->createNamedParameter(Configuration::PAGE_TYPE, Connection::PARAM_INT),
                ),
            )
            ->orderBy('uid')
            ->executeQuery()
            ->fetchAllAssociative();

        $uids = array_map(static fn (array $page): int => (int) $page['uid'], $pages);

        $rootUids = [];
        foreach ($pages as $page) {
            if (!in_array((int) $page['pid'], $uids, true)) {
                $rootUids[] = (int) $page['uid'];
            }
        }

        $this->exampleRootPageUids = $rootUids;

        return $rootUids;
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
