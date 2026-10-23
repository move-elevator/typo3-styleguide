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

use function array_filter;
use function array_map;
use function array_values;
use function explode;
use function trim;

/**
 * SplitViewHelper.
 *
 * Splits a comma separated string into its trimmed, non-empty parts.
 *
 * @author Konrad Michalik <km@move-elevator.de>
 * @license GPL-2.0-or-later
 */
class SplitViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        $this->registerArgument('value', 'string', 'Comma separated string to split');
    }

    /**
     * @return list<string>
     */
    public function render(): array
    {
        return self::split((string) $this->arguments['value']);
    }

    /**
     * @return list<string>
     */
    public static function split(string $value): array
    {
        return array_values(array_filter(
            array_map(trim(...), explode(',', $value)),
            static fn (string $part): bool => '' !== $part,
        ));
    }
}
