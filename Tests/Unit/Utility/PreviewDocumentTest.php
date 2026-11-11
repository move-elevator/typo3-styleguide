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

namespace MoveElevator\Styleguide\Tests\Unit\Utility;

use MoveElevator\Styleguide\Utility\PreviewDocument;
use PHPUnit\Framework\TestCase;

/**
 * PreviewDocumentTest.
 *
 * @author Konrad Michalik <km@move-elevator.de>
 * @license GPL-2.0-or-later
 */
final class PreviewDocumentTest extends TestCase
{
    private const PAGE = <<<'HTML'
        <!DOCTYPE html>
        <html lang="de"><head><meta charset="utf-8"><title>T</title><link rel="stylesheet" href="/app.css"></head>
        <body class="theme"><header>Navigation</header>
        <main class="grid"><!--sg-preview-start:1--><section>First Ä</section><!--sg-preview-end:1-->
        <!--sg-preview-start:2--><section>Second</section><!--sg-preview-end:2--></main>
        <footer>Footer</footer><script src="/app.js"></script></body></html>
        HTML;

    public function testIsolateKeepsOnlyTheElementItsAncestorsAndTheAssets(): void
    {
        $result = PreviewDocument::isolate(self::PAGE, 2);

        self::assertNotNull($result);
        self::assertStringContainsString('<section>Second</section>', $result);
        self::assertStringContainsString('<main class="grid">', $result);
        self::assertStringContainsString('<body class="theme">', $result);
        self::assertStringContainsString('href="/app.css"', $result);
        self::assertStringContainsString('src="/app.js"', $result);
        self::assertStringNotContainsString('First', $result);
        self::assertStringNotContainsString('Navigation', $result);
        self::assertStringNotContainsString('Footer', $result);
        self::assertStringNotContainsString('sg-preview', $result);
    }

    public function testIsolateKeepsTheDoctypeSoTheBrowserDoesNotUseQuirksMode(): void
    {
        $result = PreviewDocument::isolate(self::PAGE, 1);

        self::assertNotNull($result);
        self::assertStringStartsWith('<!DOCTYPE html>', $result);
    }

    public function testIsolateKeepsADoctypeWithIdentifiers(): void
    {
        $page = str_replace('<!DOCTYPE html>', '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">', self::PAGE);

        $result = PreviewDocument::isolate($page, 1);

        self::assertNotNull($result);
        self::assertStringStartsWith('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">', $result);
    }

    public function testIsolateRemovesThePageBackground(): void
    {
        $result = PreviewDocument::isolate(self::PAGE, 1);

        self::assertNotNull($result);
        self::assertStringContainsString('html,body{background:none!important}', $result);
    }

    public function testIsolateKeepsUmlautsIntact(): void
    {
        $result = PreviewDocument::isolate(self::PAGE, 1);

        self::assertNotNull($result);
        self::assertStringContainsString('First Ä', $result);
        self::assertStringNotContainsString('<?xml', $result);
    }

    public function testIsolateReturnsNullWhenTheElementIsNotOnThePage(): void
    {
        self::assertNull(PreviewDocument::isolate(self::PAGE, 3));
    }

    public function testIsolateDoesNotMatchAUidThatOnlyStartsWithTheNumber(): void
    {
        self::assertNull(PreviewDocument::isolate(str_replace(':2-->', ':23-->', self::PAGE), 2));
    }
}
