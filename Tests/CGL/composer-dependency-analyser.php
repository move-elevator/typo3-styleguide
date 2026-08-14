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

use Composer\Autoload;
use ShipMonk\ComposerDependencyAnalyser;
use ShipMonk\ComposerDependencyAnalyser\Config\ErrorType;

$rootPath = dirname(__DIR__, 2);

/** @var Autoload\ClassLoader $loader */
$loader = require $rootPath.'/vendor/autoload.php';
$loader->register();

$configuration = new ComposerDependencyAnalyser\Config\Configuration();
$configuration
    ->addPathToScan($rootPath.'/Configuration', false)
    ->addPathsToExclude([
        $rootPath.'/Tests/CGL',
    ])
    // Optional dependency: only a "suggest", guarded in Configuration/Services.php.
    ->ignoreErrorsOnPackage('hn/typo3-mcp-server', [ErrorType::DEV_DEPENDENCY_IN_PROD])
;

return $configuration;
