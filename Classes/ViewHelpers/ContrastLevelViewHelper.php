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

namespace MoveElevator\Styleguide\ViewHelpers;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * ContrastLevelViewHelper.
 *
 * Maps a contrast ratio to the WCAG 2.x level it reaches: `aaa` (7:1), `aa` (4.5:1), `large`
 * (3:1, large text and UI components only) or `fail`.
 *
 * @author Konrad Michalik <km@move-elevator.de>
 * @license GPL-2.0-or-later
 */
class ContrastLevelViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        $this->registerArgument('ratio', 'float', 'Contrast ratio, e.g. from sg:contrastRatio', true);
    }

    public function render(): string
    {
        $ratio = (float) $this->arguments['ratio'];

        return match (true) {
            $ratio >= 7.0 => 'aaa',
            $ratio >= 4.5 => 'aa',
            $ratio >= 3.0 => 'large',
            default => 'fail',
        };
    }
}
