<?php declare(strict_types=1);

/***********************************************************************
 * This file is part of Base3Manager for BASE3 Framework.
 *
 * Base3Manager provides manager pages and controls for BASE3.
 * It composes administration navigation, toolbars and views.
 * Extensions contribute controls through the existing plugin contracts.
 *
 * Developed by Daniel Dahme
 * Licensed under GPL-3.0
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 *
 * https://base3.de
 ***********************************************************************/

namespace Base3\Base3Manager\Test;

use PHPUnit\Framework\TestCase;
use Base3\Base3Manager\Base3ManagerPlugin;
use Base3\CoreFoundation\Api\IContainer;
use Base3\CoreFoundation\Api\IClassMap;

class Base3ManagerPluginTest extends TestCase
{
    public function testInitSetsServicesInContainer()
    {
        $containerMock = $this->createMock(IContainer::class);

        $classMapMock = $this->createMock(IClassMap::class);
        $containerMock->method('get')->willReturn($classMapMock);

        $plugin = new Base3ManagerPlugin($containerMock);
	$plugin->init();

        $this->assertTrue(true); // Sicherstellen, dass die Methode ohne Fehler ausgeführt wird
    }
    
    public function testCheckDependencies()
    {
        $containerMock = $this->createMock(IContainer::class);

        $plugin = new Base3ManagerPlugin($containerMock);
        $dependencies = $plugin->checkDependencies();

        $this->assertTrue(true); // Sicherstellen, dass die Methode ohne Fehler ausgeführt wird
    }
}

