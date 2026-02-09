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

namespace MoveElevator\Styleguide\ViewHelpers\Format;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

use function is_object;
use function is_string;
use function strlen;

/**
 * SlugViewHelper.
 *
 * @author Konrad Michalik <km@move-elevator.de>
 * @license GPL-2.0-or-later
 */
class SlugViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        $this->registerArgument('value', 'string', 'String to format');
    }

    public function render(): string
    {
        $value = $this->renderChildren();
        if (!is_string($value) && !(is_object($value) && method_exists($value, '__toString'))) {
            /* @phpstan-ignore-next-line */
            return $value;
        }

        return self::generateSlug((string) $value);
    }

    public function getContentArgumentName(): string
    {
        return 'value';
    }

    public static function generateSlug(string $string, ?string $fallback = null, int $maxLength = 128): string
    {
        $string = trim(strtolower($string));

        $string = str_replace(['ä', 'ö', 'ü', 'ß'], ['ae', 'oe', 'ue', 'ss'], $string);

        // use transliterate for cyrillic and hebrew characters
        // https://www.php.net/manual/en/transliterator.transliterate.php
        $transliterated = transliterator_transliterate('Any-Latin; Latin-ASCII', $string);
        if (false === $transliterated) {
            return $fallback ?? '';
        }
        $string = $transliterated;

        $string = preg_replace('/[^a-z0-9]/', '-', $string);
        if (null === $string) {
            return $fallback ?? '';
        }

        while (str_contains($string, '--')) {
            $string = str_replace('--', '-', $string);
        }

        if (strlen($string) > $maxLength) {
            $string = substr($string, 0, $maxLength);
        }

        if ('' === $string || '-' === $string) {
            return $fallback ?? '';
        }

        return $string;
    }
}
