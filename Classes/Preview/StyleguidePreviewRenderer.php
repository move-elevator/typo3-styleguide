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
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\{GeneralUtility, PathUtility};

use function htmlspecialchars;
use function is_array;

use const ENT_QUOTES;

/**
 * StyleguidePreviewRenderer.
 *
 * @author Konrad Michalik <km@move-elevator.de>
 * @license GPL-2.0-or-later
 */
class StyleguidePreviewRenderer extends StandardContentPreviewRenderer
{
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

        $items = '';
        foreach ($children as $child) {
            $color = htmlspecialchars((string) $child['color'], ENT_QUOTES);
            $label = htmlspecialchars((string) $child['label'], ENT_QUOTES);
            $items .= '<div style="display:inline-flex;align-items:center;margin:0 12px 6px 0;">'
                .'<span style="display:inline-block;width:20px;height:20px;border-radius:4px;background:'.$color.';border:1px solid rgba(0,0,0,.15);flex-shrink:0;"></span>'
                .'<span style="margin-left:6px;font-size:11px;line-height:1.2;">'.$color
                .('' !== $label ? '<br><span style="color:#666;">'.$label.'</span>' : '')
                .'</span></div>';
        }

        return '<div style="display:flex;flex-wrap:wrap;">'.$items.'</div>';
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

        $items = '';
        foreach ($children as $child) {
            $font = htmlspecialchars((string) $child['font'], ENT_QUOTES);
            $weight = htmlspecialchars((string) $child['font_weight'], ENT_QUOTES);
            $label = htmlspecialchars((string) $child['label'], ENT_QUOTES);
            $displayName = '' !== $label ? $label : $font;

            $style = 'font-family:'.$font.';font-size:16px;';
            if ('' !== $weight) {
                $style .= 'font-weight:'.$weight.';';
            }

            $items .= '<div style="margin-bottom:8px;">'
                .'<div style="font-size:11px;color:#666;margin-bottom:2px;">'.$displayName
                .('' !== $weight ? ' ('.$weight.')' : '')
                .'</div>'
                .'<div style="'.$style.'">AaBbCc 123</div>'
                .'</div>';
        }

        return $items;
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
        if ('' === $absDir || !is_dir($absDir)) {
            return '<span style="font-size:11px;color:#666;">'.htmlspecialchars($path, ENT_QUOTES).'</span>';
        }

        $files = scandir($absDir);
        if (false === $files) {
            return '<span style="font-size:11px;color:#666;">'.htmlspecialchars($path, ENT_QUOTES).'</span>';
        }

        $items = '';
        $count = 0;
        foreach ($files as $file) {
            if ('.' === $file || '..' === $file || !is_file($absDir.'/'.$file)) {
                continue;
            }

            ++$count;
            if ($count > 20) {
                $items .= '<div style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;font-size:11px;color:#666;">…</div>';

                break;
            }

            $webPath = PathUtility::getAbsoluteWebPath($absDir.'/'.$file);
            $name = htmlspecialchars(pathinfo($file, \PATHINFO_FILENAME), ENT_QUOTES);
            $items .= '<div style="display:inline-block;margin:0 4px 4px 0;text-align:center;" title="'.$name.'">'
                .'<img src="'.htmlspecialchars($webPath, ENT_QUOTES).'" alt="'.$name.'" style="width:32px;height:32px;object-fit:contain;" loading="lazy" />'
                .'</div>';
        }

        return '<div style="font-size:11px;color:#666;margin-bottom:4px;">'.htmlspecialchars($path, ENT_QUOTES).'</div>'
            .'<div style="display:flex;flex-wrap:wrap;align-items:center;">'.$items.'</div>';
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

        $items = '';
        foreach ($children as $child) {
            $extPath = (string) $child['path'];
            $caption = htmlspecialchars((string) $child['caption'], ENT_QUOTES);
            $absPath = GeneralUtility::getFileAbsFileName($extPath);
            $webPath = '' !== $absPath ? PathUtility::getAbsoluteWebPath($absPath) : '';

            if ('' !== $webPath) {
                $items .= '<div style="display:inline-block;margin:0 8px 8px 0;text-align:center;">'
                    .'<img src="'.htmlspecialchars($webPath, ENT_QUOTES).'" alt="'.$caption.'" style="max-width:64px;max-height:64px;border:1px solid rgba(0,0,0,.1);border-radius:4px;" loading="lazy" />'
                    .('' !== $caption ? '<div style="font-size:10px;color:#666;margin-top:2px;max-width:64px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">'.$caption.'</div>' : '')
                    .'</div>';
            } else {
                $items .= '<div style="display:inline-block;margin:0 8px 8px 0;font-size:11px;color:#666;">'.htmlspecialchars($extPath, ENT_QUOTES).'</div>';
            }
        }

        return '<div style="display:flex;flex-wrap:wrap;align-items:flex-start;">'.$items.'</div>';
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
