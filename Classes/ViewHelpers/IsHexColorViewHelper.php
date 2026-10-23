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

use MoveElevator\Styleguide\Utility\ColorUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * IsHexColorViewHelper.
 *
 * Tells whether a value is a six digit HEX color, so a template can skip invalid values instead of failing.
 *
 * @author Konrad Michalik <km@move-elevator.de>
 * @license GPL-2.0-or-later
 */
class IsHexColorViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        $this->registerArgument('color', 'string', 'HEX Code', true);
    }

    public function render(): bool
    {
        return ColorUtility::isHex((string) $this->arguments['color']);
    }
}
