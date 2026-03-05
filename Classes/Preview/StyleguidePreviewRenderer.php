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

use TYPO3\CMS\Backend\Preview\StandardContentPreviewRenderer;
use TYPO3\CMS\Backend\View\BackendLayout\Grid\GridColumnItem;
use TYPO3\CMS\Core\Database\{Connection, ConnectionPool};
use TYPO3\CMS\Core\Utility\{GeneralUtility, PathUtility};

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

    public function renderPageModulePreviewContent(GridColumnItem $item): string
    {
        $row = $this->getRowFromItem($item);
        $cType = (string) ($row['CType'] ?? '');

        return match ($cType) {
            'metypo3styleguide_technicalheadline' => $this->renderTechnicalHeadlinePreview($row),
            'metypo3styleguide_colors' => $this->renderColorsPreview($row),
            'metypo3styleguide_fonts' => $this->renderFontsPreview($row),
            'metypo3styleguide_icons' => $this->renderIconsPreview($row),
            'metypo3styleguide_images' => $this->renderImagesPreview($row),
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
        $children = $this->getChildRecords('tx_metypo3styleguide_color', (int) $row['uid']);
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
        $children = $this->getChildRecords('tx_metypo3styleguide_font', (int) $row['uid']);
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
        $path = (string) ($row['tx_metypo3styleguide_icons_path'] ?? '');
        if ('' === $path) {
            return '';
        }

        $absDir = GeneralUtility::getFileAbsFileName($path);
        $files = ('' !== $absDir && is_dir($absDir)) ? scandir($absDir) : false;
        if (false === $files) {
            return $this->renderFluidPreview('Icons', ['path' => $path, 'icons' => [], 'hasMore' => false]);
        }

        $icons = [];
        $hasMore = false;
        $count = 0;
        foreach ($files as $file) {
            if ('.' === $file || '..' === $file || !is_file($absDir.'/'.$file)) {
                continue;
            }

            ++$count;
            if ($count > 20) {
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
    private function renderImagesPreview(array $row): string
    {
        $children = $this->getChildRecords('tx_metypo3styleguide_image', (int) $row['uid']);
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
     * @param array<string, mixed> $variables
     */
    private function renderFluidPreview(string $templateName, array $variables): string
    {
        // TYPO3 v13+: Use ViewFactoryInterface
        if (class_exists(\TYPO3\CMS\Core\View\ViewFactoryData::class)) {
            /** @var \TYPO3\CMS\Core\View\ViewFactoryInterface $viewFactory */
            $viewFactory = GeneralUtility::makeInstance(\TYPO3\CMS\Core\View\ViewFactoryInterface::class);
            $view = $viewFactory->create(new \TYPO3\CMS\Core\View\ViewFactoryData(
                templateRootPaths: [GeneralUtility::getFileAbsFileName(self::TEMPLATE_PATH)],
            ));
            $view->assignMultiple($variables);

            return $view->render($templateName);
        }

        // TYPO3 v12 fallback: StandaloneView
        /** @var \TYPO3\CMS\Fluid\View\StandaloneView $view */
        $view = GeneralUtility::makeInstance(\TYPO3\CMS\Fluid\View\StandaloneView::class); // @phpstan-ignore classConstant.deprecatedClass
        $view->setTemplatePathAndFilename( // @phpstan-ignore method.deprecatedClass
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

        // @phpstan-ignore function.alreadyNarrowedType (v13 returns array, v14 returns RecordInterface)
        if (is_array($record)) {
            return $record;
        }

        // @phpstan-ignore deadCode.unreachable
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
