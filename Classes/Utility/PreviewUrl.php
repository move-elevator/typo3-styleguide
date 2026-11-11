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

namespace MoveElevator\Styleguide\Utility;

use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 * PreviewUrl.
 *
 * The address of a page for a preview frame. The root page of a site is reached with and without a
 * slash at the end, but a base redirect of a project may send the form with the slash to a
 * different scheme or host, which the frame refuses to load. So the address of a root page gets none.
 *
 * @author Konrad Michalik <km@move-elevator.de>
 * @license GPL-2.0-or-later
 */
final class PreviewUrl
{
    public static function forPage(ContentObjectRenderer $cObj, int $pageId, string $additionalParams = ''): string
    {
        $url = $cObj->typoLink_URL(['parameter' => (string) $pageId, 'additionalParams' => $additionalParams]);
        $site = $cObj->getRequest()->getAttribute('site');

        return $site instanceof Site && $site->getRootPageId() === $pageId ? self::withoutTrailingSlash($url) : $url;
    }

    /**
     * Removes the slash at the end of the path. The address of a host alone, `/`, stays as it is.
     */
    public static function withoutTrailingSlash(string $url): string
    {
        return preg_replace('/^([^?#]*?[^\/?#])\/+(?=[?#]|$)/', '$1', $url) ?? $url;
    }
}
