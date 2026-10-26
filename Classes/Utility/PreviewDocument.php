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

use DOMComment;
use DOMDocument;
use DOMNode;
use DOMXPath;

use function in_array;
use function sprintf;

/**
 * PreviewDocument.
 *
 * Reduces a rendered page to one content element. The element is found by the comment markers the
 * TypoScript adds around it, so no id or class of the project is needed. Everything outside the
 * element is removed, except the ancestors (they keep the grid and the classes the project styles
 * the element with) and the scripts, styles and links, which the element may depend on.
 *
 * @author Konrad Michalik <km@move-elevator.de>
 * @license GPL-2.0-or-later
 */
final class PreviewDocument
{
    private const KEEP_TAGS = ['script', 'style', 'link', 'noscript', 'template'];

    // The page background is a decoration of the project's page and not part of the element
    private const PAGE_STYLE = 'html,body{background:none!important}';

    public static function isolate(string $html, int $uid): ?string
    {
        $previous = libxml_use_internal_errors(true);
        $document = new DOMDocument();
        // The prefix makes libxml read the page as UTF-8 even without a charset declaration
        $loaded = $document->loadHTML('<?xml encoding="UTF-8">'.$html);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);
        if (!$loaded) {
            return null;
        }

        $xpath = new DOMXPath($document);
        $start = self::marker($xpath, 'start', $uid);
        $end = self::marker($xpath, 'end', $uid);
        $body = $document->getElementsByTagName('body')->item(0);
        if (null === $start || null === $end || null === $body || $start->parentNode !== $end->parentNode) {
            return null;
        }

        self::removeOutside($start, $end);
        for ($node = $start->parentNode; null !== $node && $node !== $body; $node = $node->parentNode) {
            self::removeSiblings($node);
        }

        $start->remove();
        $end->remove();
        self::addStyle($document, $body);
        foreach (iterator_to_array($document->childNodes) as $child) {
            if (\XML_PI_NODE === $child->nodeType) {
                $document->removeChild($child);
            }
        }

        // Saving the whole document writes non-ASCII characters as entities, saving the nodes does not
        $root = $document->documentElement;
        if (null === $root) {
            return null;
        }

        return self::doctype($document).$document->saveHTML($root);
    }

    /**
     * Without the doctype the browser renders the page in quirks mode, which changes the layout (a form, for example, gets a bottom margin).
     * DOMDocument::saveHTML() writes nothing for the doctype node, so it is rebuilt from its parts.
     */
    private static function doctype(DOMDocument $document): string
    {
        $doctype = $document->doctype;
        if (null === $doctype) {
            return '';
        }

        $declaration = '<!DOCTYPE '.$doctype->name;
        if ('' !== $doctype->publicId) {
            $declaration .= ' PUBLIC "'.$doctype->publicId.'"'.('' !== $doctype->systemId ? ' "'.$doctype->systemId.'"' : '');
        } elseif ('' !== $doctype->systemId) {
            $declaration .= ' SYSTEM "'.$doctype->systemId.'"';
        }

        return $declaration.">\n";
    }

    private static function addStyle(DOMDocument $document, DOMNode $body): void
    {
        $head = $document->getElementsByTagName('head')->item(0) ?? $body;
        $head->appendChild($document->createElement('style', self::PAGE_STYLE));
    }

    private static function marker(DOMXPath $xpath, string $kind, int $uid): ?DOMComment
    {
        $nodes = $xpath->query(sprintf('//comment()[normalize-space(.)="sg-preview-%s:%d"]', $kind, $uid));
        $node = false !== $nodes ? $nodes->item(0) : null;

        return $node instanceof DOMComment ? $node : null;
    }

    private static function removeOutside(DOMNode $start, DOMNode $end): void
    {
        $parent = $start->parentNode;
        if (null === $parent) {
            return;
        }

        $inside = false;
        foreach (iterator_to_array($parent->childNodes) as $child) {
            if ($child === $start) {
                $inside = true;
            }
            if (!$inside && !self::isKept($child)) {
                $parent->removeChild($child);
            }
            if ($child === $end) {
                $inside = false;
            }
        }
    }

    private static function removeSiblings(DOMNode $node): void
    {
        $parent = $node->parentNode;
        if (null === $parent) {
            return;
        }

        foreach (iterator_to_array($parent->childNodes) as $sibling) {
            if ($sibling !== $node && !self::isKept($sibling)) {
                $parent->removeChild($sibling);
            }
        }
    }

    private static function isKept(DOMNode $node): bool
    {
        return in_array(strtolower($node->nodeName), self::KEEP_TAGS, true);
    }
}
