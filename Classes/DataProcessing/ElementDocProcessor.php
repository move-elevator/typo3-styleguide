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

namespace MoveElevator\Styleguide\DataProcessing;

use MoveElevator\Styleguide\Utility\ElementFieldCollector;
use TYPO3\CMS\Core\Domain\Repository\PageRepository;
use TYPO3\CMS\Core\Localization\LanguageServiceFactory;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\{ContentObjectRenderer, DataProcessorInterface};

use function array_slice;
use function count;
use function is_array;
use function is_string;

/**
 * ElementDocProcessor.
 *
 * Prepares the documentation of a content type: its fields from the TCA and live examples. The
 * examples come only from the pages the editor named at the element, and only from pages the
 * visitor may see. Nothing in the request chooses which records are rendered.
 *
 * @author Konrad Michalik <km@move-elevator.de>
 * @license GPL-2.0-or-later
 */
final class ElementDocProcessor implements DataProcessorInterface
{
    private const DOC_CTYPE = 'typo3styleguide_elementdoc';
    private const HEADLINE_CTYPE = 'typo3styleguide_technicalheadline';
    private const MAX_EXAMPLES = 12;

    /**
     * @param array<mixed> $contentObjectConfiguration
     * @param array<mixed> $processorConfiguration
     * @param array<mixed> $processedData
     *
     * @return array<mixed>
     */
    public function process(ContentObjectRenderer $cObj, array $contentObjectConfiguration, array $processorConfiguration, array $processedData): array
    {
        $language = $cObj->getRequest()->getAttribute('language');
        if (!$language instanceof SiteLanguage) {
            return $processedData;
        }

        $cType = (string) ($cObj->data['tx_typo3styleguide_elementdoc_ctype'] ?? '');
        $languageService = GeneralUtility::makeInstance(LanguageServiceFactory::class)->createFromSiteLanguage($language);
        $translate = static fn (string $key): string => $languageService->sL($key);

        $processedData['elementCType'] = $cType;
        $processedData['elementLabel'] = $this->cTypeLabel($cType, $translate);
        $processedData['fields'] = '' === $cType ? [] : (new ElementFieldCollector())->collect($cType, $GLOBALS['TCA']['tt_content'], $translate);
        $processedData['examples'] = '' === $cType ? [] : $this->examples(
            $cObj,
            $cType,
            GeneralUtility::intExplode(',', (string) ($cObj->data['tx_typo3styleguide_elementdoc_pages'] ?? ''), true),
        );

        return $processedData;
    }

    /**
     * @param callable(string): string $translate
     */
    private function cTypeLabel(string $cType, callable $translate): string
    {
        $items = $GLOBALS['TCA']['tt_content']['columns']['CType']['config']['items'] ?? [];
        foreach (is_array($items) ? $items : [] as $item) {
            if (is_array($item) && ($item['value'] ?? null) === $cType && is_string($item['label'] ?? null)) {
                return $translate($item['label']);
            }
        }

        return $cType;
    }

    /**
     * @param list<int> $pageIds
     *
     * @return list<array{name: string, html: string}>
     */
    private function examples(ContentObjectRenderer $cObj, string $cType, array $pageIds): array
    {
        if (self::DOC_CTYPE === $cType) {
            return [];
        }

        $examples = [];
        foreach (GeneralUtility::makeInstance(PageRepository::class)->filterAccessiblePageIds($pageIds) as $pageId) {
            $headline = '';
            $rows = $cObj->getRecords('tt_content', ['pidInList' => (string) $pageId, 'orderBy' => 'sorting']);
            foreach ($rows as $row) {
                if (self::HEADLINE_CTYPE === $row['CType']) {
                    $headline = (string) $row['header'];

                    continue;
                }
                if ($cType !== $row['CType'] || count($examples) >= self::MAX_EXAMPLES) {
                    continue;
                }

                $examples[] = [
                    'name' => '' !== $headline ? $headline : (string) $row['header'],
                    'html' => $cObj->cObjGetSingle('RECORDS', ['tables' => 'tt_content', 'source' => (string) (int) $row['uid']]),
                ];
            }
        }

        return array_slice($examples, 0, self::MAX_EXAMPLES);
    }
}
