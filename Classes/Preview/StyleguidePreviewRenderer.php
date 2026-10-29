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

namespace MoveElevator\Styleguide\Preview;

use MoveElevator\Styleguide\Utility\FileListUtility;
use TYPO3\CMS\Backend\Preview\StandardContentPreviewRenderer;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Backend\View\BackendLayout\Grid\GridColumnItem;
use TYPO3\CMS\Core\Database\{Connection, ConnectionPool};
use TYPO3\CMS\Core\Utility\{GeneralUtility, PathUtility};

use function count;
use function htmlspecialchars;
use function is_array;

use const ENT_QUOTES;
use const PATHINFO_FILENAME;

/**
 * StyleguidePreviewRenderer.
 *
 * @author Konrad Michalik <km@move-elevator.de>
 * @license GPL-2.0-or-later
 */
class StyleguidePreviewRenderer extends StandardContentPreviewRenderer
{
    private const TEMPLATE_PATH = 'EXT:typo3_styleguide/Resources/Private/Templates/Preview/';

    private const LAYOUT_LABELS = [
        'list' => 'List',
        'cards' => 'Cards',
        'groups' => 'Groups',
        'areas' => 'Areas',
    ];

    public function renderPageModulePreviewContent(GridColumnItem $item): string
    {
        $row = $this->getRowFromItem($item);
        $cType = (string) ($row['CType'] ?? '');

        return match ($cType) {
            'typo3styleguide_technicalheadline' => $this->renderTechnicalHeadlinePreview($row),
            'typo3styleguide_colors' => $this->renderColorsPreview($row),
            'typo3styleguide_fonts' => $this->renderFontsPreview($row),
            'typo3styleguide_icons' => $this->renderIconsPreview($row),
            'typo3styleguide_images' => $this->renderImagesPreview($row),
            'typo3styleguide_tableofcontents' => $this->renderTableOfContentsPreview($row),
            'typo3styleguide_pagelinks' => $this->renderPageLinksPreview($row),
            'typo3styleguide_imagesizes' => $this->renderImageSizesPreview($row),
            'typo3styleguide_gallery' => $this->renderGalleryPreview($row),
            'typo3styleguide_guidelines' => $this->renderGuidelinesPreview($row),
            'typo3styleguide_elementdoc' => $this->renderElementDocPreview($row),
            'typo3styleguide_pagepreview' => $this->renderPagePreviewPreview($row),
            default => parent::renderPageModulePreviewContent($item),
        };
    }

    /**
     * @param array<string, mixed> $row
     */
    private function renderTechnicalHeadlinePreview(array $row): string
    {
        $bodytext = (string) ($row['bodytext'] ?? '');
        if ('' === $bodytext) {
            return '';
        }

        return nl2br(htmlspecialchars(GeneralUtility::fixed_lgd_cs(strip_tags($bodytext), 500), ENT_QUOTES));
    }

    /**
     * @param array<string, mixed> $row
     */
    private function renderColorsPreview(array $row): string
    {
        $children = $this->getChildRecords('tx_typo3styleguide_color', (int) $row['uid']);
        if ([] === $children) {
            return '';
        }

        return $this->renderFluidPreview('Colors', ['children' => $children]);
    }

    /**
     * @param array<string, mixed> $row
     */
    private function renderFontsPreview(array $row): string
    {
        $children = $this->getChildRecords('tx_typo3styleguide_font', (int) $row['uid']);
        if ([] === $children) {
            return '';
        }

        $fonts = [];
        foreach ($children as $child) {
            $font = (string) $child['font'];
            $weight = (string) $child['font_weight'];
            $label = (string) $child['label'];

            $style = 'font-family:'.$font.';font-size:16px;';
            if ('' !== $weight) {
                $style .= 'font-weight:'.$weight.';';
            }

            $fonts[] = [
                'label' => '' !== $label ? $label : $font,
                'weight' => $weight,
                'style' => $style,
            ];
        }

        return $this->renderFluidPreview('Fonts', ['fonts' => $fonts]);
    }

    /**
     * @param array<string, mixed> $row
     */
    private function renderIconsPreview(array $row): string
    {
        $path = (string) ($row['tx_typo3styleguide_icons_path'] ?? '');
        if ('' === $path) {
            return '';
        }

        $absDir = GeneralUtility::getFileAbsFileName($path);
        $files = FileListUtility::listFiles($absDir, (string) ($row['tx_typo3styleguide_icons_exclude'] ?? ''));

        $icons = [];
        $hasMore = false;
        foreach ($files as $file) {
            if (count($icons) >= 20) {
                $hasMore = true;

                break;
            }

            $icons[] = [
                'name' => pathinfo($file, PATHINFO_FILENAME),
                'webPath' => PathUtility::getAbsoluteWebPath($absDir.'/'.$file),
            ];
        }

        return $this->renderFluidPreview('Icons', ['path' => $path, 'icons' => $icons, 'hasMore' => $hasMore]);
    }

    /**
     * @param array<string, mixed> $row
     */
    private function renderImageSizesPreview(array $row): string
    {
        $children = $this->getChildRecords('tx_typo3styleguide_imagesize', (int) $row['uid']);
        if ([] === $children) {
            return '';
        }

        $sizes = [];
        foreach ($children as $child) {
            $channel = (string) $child['channel'];
            $type = (string) $child['image_type'];

            $sizes[] = [
                'label' => '' !== $type ? $channel.', '.$type : $channel,
                'width' => (int) $child['width'],
                'height' => (int) $child['height'],
            ];
        }

        return $this->renderFluidPreview('ImageSizes', ['sizes' => $sizes]);
    }

    /**
     * @param array<string, mixed> $row
     */
    private function renderImagesPreview(array $row): string
    {
        $children = $this->getChildRecords('tx_typo3styleguide_image', (int) $row['uid']);
        if ([] === $children) {
            return '';
        }

        $images = [];
        foreach ($children as $child) {
            $extPath = (string) $child['path'];
            $absPath = GeneralUtility::getFileAbsFileName($extPath);

            $images[] = [
                'path' => $extPath,
                'caption' => (string) $child['caption'],
                'webPath' => '' !== $absPath && is_file($absPath) ? PathUtility::getAbsoluteWebPath($absPath) : '',
            ];
        }

        return $this->renderFluidPreview('Images', ['images' => $images]);
    }

    /**
     * @param array<string, mixed> $row
     */
    private function renderTableOfContentsPreview(array $row): string
    {
        return $this->renderFluidPreview('TableOfContents', [
            'layout' => $this->getLayoutLabel($row),
        ]);
    }

    /**
     * @param array<string, mixed> $row
     */
    private function renderPageLinksPreview(array $row): string
    {
        $pageTitles = [];
        foreach (GeneralUtility::intExplode(',', (string) ($row['pages'] ?? ''), true) as $pageUid) {
            $page = BackendUtility::getRecord('pages', $pageUid, 'title');
            if (null !== $page) {
                $pageTitles[] = (string) $page['title'];
            }
        }

        return $this->renderFluidPreview('PageLinks', [
            'layout' => $this->getLayoutLabel($row),
            'pageTitles' => $pageTitles,
        ]);
    }

    /**
     * @param array<string, mixed> $row
     */
    private function getLayoutLabel(array $row): string
    {
        $layout = (string) ($row['tx_typo3styleguide_tableofcontents_layout'] ?? 'list');

        return self::LAYOUT_LABELS[$layout] ?? $layout;
    }

    /**
     * @param array<string, mixed> $row
     */
    private function renderGalleryPreview(array $row): string
    {
        return $this->renderFluidPreview('Gallery', [
            'count' => (int) ($row['tx_typo3styleguide_gallery_images'] ?? 0),
            'columns' => (string) ($row['tx_typo3styleguide_gallery_columns'] ?? ''),
        ]);
    }

    /**
     * @param array<string, mixed> $row
     */
    private function renderElementDocPreview(array $row): string
    {
        return $this->renderFluidPreview('ElementDoc', [
            'cType' => (string) ($row['tx_typo3styleguide_elementdoc_ctype'] ?? ''),
            'pageCount' => count(GeneralUtility::intExplode(',', (string) ($row['tx_typo3styleguide_elementdoc_pages'] ?? ''), true)),
        ]);
    }

    /**
     * @param array<string, mixed> $row
     */
    private function renderPagePreviewPreview(array $row): string
    {
        return $this->renderFluidPreview('PagePreview', [
            'page' => GeneralUtility::intExplode(',', (string) ($row['tx_typo3styleguide_pagepreview_page'] ?? ''), true)[0] ?? 0,
        ]);
    }

    /**
     * @param array<string, mixed> $row
     */
    private function renderGuidelinesPreview(array $row): string
    {
        $children = $this->getChildRecords('tx_typo3styleguide_guideline', (int) $row['uid']);
        if ([] === $children) {
            return '';
        }

        return $this->renderFluidPreview('Guidelines', ['guidelines' => array_map(
            static fn (array $child): array => [
                'type' => (string) $child['type'],
                'text' => GeneralUtility::fixed_lgd_cs((string) $child['text'], 90),
            ],
            $children,
        )]);
    }

    /**
     * @param array<string, mixed> $variables
     */
    private function renderFluidPreview(string $templateName, array $variables): string
    {
        // TYPO3 v13+: Use ViewFactoryInterface
        if (class_exists(\TYPO3\CMS\Core\View\ViewFactoryData::class)) {
            /** @var \TYPO3\CMS\Core\View\ViewFactoryInterface $viewFactory */
            $viewFactory = GeneralUtility::makeInstance(\TYPO3\CMS\Core\View\ViewFactoryInterface::class);
            $view = $viewFactory->create(new \TYPO3\CMS\Core\View\ViewFactoryData(
                [GeneralUtility::getFileAbsFileName(self::TEMPLATE_PATH)],
            ));
            $view->assignMultiple($variables);

            return $view->render($templateName);
        }

        // TYPO3 v12: StandaloneView, removed in v14
        $view = GeneralUtility::makeInstance(\TYPO3\CMS\Fluid\View\StandaloneView::class);
        $view->setTemplatePathAndFilename(
            GeneralUtility::getFileAbsFileName(self::TEMPLATE_PATH.$templateName.'.html'),
        );
        $view->assignMultiple($variables);

        return $view->render();
    }

    /**
     * Extract the record row as array, compatible with both TYPO3 v13 (array) and v14 (RecordInterface).
     *
     * @return array<string, mixed>
     */
    private function getRowFromItem(GridColumnItem $item): array
    {
        $record = $item->getRecord();

        // v13 returns array, v14 returns RecordInterface
        if (is_array($record)) {
            return $record;
        }

        return $record->getRawRecord()?->toArray() ?? [];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function getChildRecords(string $table, int $parentId): array
    {
        if ($parentId <= 0) {
            return [];
        }

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable($table);

        return $queryBuilder
            ->select('*')
            ->from($table)
            ->where(
                $queryBuilder->expr()->eq('parentid', $queryBuilder->createNamedParameter($parentId, Connection::PARAM_INT)),
                $queryBuilder->expr()->eq('parenttable', $queryBuilder->createNamedParameter('tt_content')),
            )
            ->orderBy('sorting')
            ->executeQuery()
            ->fetchAllAssociative();
    }
}
