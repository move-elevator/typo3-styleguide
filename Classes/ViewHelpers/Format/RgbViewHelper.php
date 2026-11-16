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

use InvalidArgumentException;
use MoveElevator\Styleguide\Utility\ColorUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

use function implode;

/**
 * RgbViewHelper.
 *
 * Returns an empty string for an invalid color, so a faulty record turns into a dropped CSS declaration and not into an error page.
 *
 * @author Konrad Michalik <km@move-elevator.de>
 * @license GPL-2.0-or-later
 */
class RgbViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        $this->registerArgument('color', 'string', 'HEX Code');
    }

    public function render(): string
    {
        try {
            return implode(', ', ColorUtility::hexToRgb((string) $this->renderChildren()));
        } catch (InvalidArgumentException) {
            return '';
        }
    }

    public function getContentArgumentName(): string
    {
        return 'color';
    }
}
