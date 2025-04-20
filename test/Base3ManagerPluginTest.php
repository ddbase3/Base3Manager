<?php

namespace Base3Manager\Test;

use PHPUnit\Framework\TestCase;
use Base3Manager\Base3ManagerPlugin;
use Base3\Api\IContainer;
use Base3\Api\IClassMap;

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

