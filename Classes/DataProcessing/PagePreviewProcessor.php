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

use MoveElevator\Styleguide\Middleware\ElementPreviewMiddleware;
use MoveElevator\Styleguide\Utility\PreviewUrl;
use TYPO3\CMS\Core\Domain\Repository\PageRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\{ContentObjectRenderer, DataProcessorInterface};

/**
 * PagePreviewProcessor.
 *
 * Prepares the preview of a whole page, for example to show a page type. The page is the one the
 * editor named at the element. It is listed when its own visibility settings (hidden, time, login
 * group) allow the visitor to see it, and the frame loads it like any visitor would.
 *
 * @author Konrad Michalik <km@move-elevator.de>
 * @license GPL-2.0-or-later
 */
final class PagePreviewProcessor implements DataProcessorInterface
{
    /**
     * @param array<mixed> $contentObjectConfiguration
     * @param array<mixed> $processorConfiguration
     * @param array<mixed> $processedData
     *
     * @return array<mixed>
     */
    public function process(ContentObjectRenderer $cObj, array $contentObjectConfiguration, array $processorConfiguration, array $processedData): array
    {
        $processedData['examples'] = [];

        $pageId = GeneralUtility::intExplode(',', (string) ($cObj->data['tx_typo3styleguide_pagepreview_page'] ?? ''), true)[0] ?? 0;
        if ($pageId <= 0) {
            return $processedData;
        }

        $repository = GeneralUtility::makeInstance(PageRepository::class);
        if ([] === $repository->filterAccessiblePageIds([$pageId])) {
            return $processedData;
        }

        $page = $repository->getPage($pageId);
        if ([] === $page) {
            return $processedData;
        }

        $processedData['examples'] = [[
            'name' => (string) $page['title'],
            'src' => PreviewUrl::forPage($cObj, $pageId, '&'.ElementPreviewMiddleware::PAGE_PARAMETER.'=1'),
        ]];

        return $processedData;
    }
}
