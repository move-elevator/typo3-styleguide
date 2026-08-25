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

use Hn\McpServer\MCP\Tool\ToolInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

/*
 * Classes below Classes/Mcp/ are built against hn/typo3-mcp-server, which is an
 * optional dependency (see "suggest"). They are excluded from the resource loader
 * in Services.yaml and registered here only when the MCP server is actually
 * available.
 *
 * The guard has to sit at container build time, not at runtime: the Symfony
 * resource loader reflects every class it picks up, so a class implementing a
 * missing interface is a fatal error during cache warmup — long before any
 * runtime check could run.
 */
return static function (ContainerConfigurator $configurator): void {
    if (!interface_exists(ToolInterface::class)) {
        return;
    }

    $configurator->services()
        ->defaults()
        ->autowire()
        ->autoconfigure()
        ->load('MoveElevator\\Styleguide\\Mcp\\', __DIR__.'/../Classes/Mcp/');
};
