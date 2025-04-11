<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Base3Manager\Page\Index;

class IndexTest extends TestCase
{
    public function testGetName()
    {
        $index = $this
            ->getMockBuilder(Index::class)
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();

        $this->assertSame('index', $index->getName());
    }

    public function testGetHelp()
    {
        $index = $this
            ->getMockBuilder(Index::class)
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();

        $this->assertSame("Help of Index\n", $index->getHelp());
    }

    public function testGetOutput()
    {
        // Dummy config array
        $dummyConfig = ['layout' => 'main'];

        // Mocks für Services
        $mockConfiguration = $this->getMockBuilder(\Base3\Configuration\Api\IConfiguration::class)
            ->setMethods(['get', 'set', 'save'])
            ->getMock();
        $mockConfiguration->method('get')->with('manager')->willReturn($dummyConfig);
        $mockConfiguration->method('set')->willReturn(null);  // nur wegen alter PHPUnit. Sonst alles mit onlyMethods statt setMethods machen
        $mockConfiguration->method('save')->willReturn(null);

        $mockLanguage = $this->getMockBuilder(stdClass::class)
            ->setMethods(['getLanguage'])
            ->getMock();
        $mockLanguage->method('getLanguage')->willReturn('de');

        $mockBase3manager = $this->getMockBuilder(stdClass::class)
            ->setMethods(['getAssets', 'getSystemNavi'])
            ->getMock();
        $mockBase3manager->method('getAssets')->willReturn(['style.css']);
        $mockBase3manager->method('getSystemNavi')->willReturn(['Dashboard', 'Settings']);

        $mockView = $this->getMockBuilder(stdClass::class)
            ->setMethods(['setPath', 'setTemplate', 'assign', 'loadTemplate'])
            ->getMock();
        $mockView->expects($this->once())->method('setPath');
        $mockView->expects($this->once())->method('setTemplate');
        $mockView->expects($this->exactly(4))->method('assign');
        $mockView->method('loadTemplate')->willReturn('rendered output');

        // ServiceLocator simulieren
        $mockServiceLocator = $this->getMockBuilder(\Base3\Core\ServiceLocator::class)
            ->setMethods(['get'])
            ->getMock();

        $mockServiceLocator->method('get')->willReturnMap([
            ['configuration', $mockConfiguration],
            ['language', $mockLanguage],
            ['base3manager', $mockBase3manager],
            ['view', $mockView],
        ]);

        // Jetzt ServiceLocator::getInstance() überschreiben
        $this->overrideServiceLocator($mockServiceLocator);

        // Klasse instanziieren mit echtem Konstruktor
        $index = new Index();

        $output = $index->getOutput();
        $this->assertSame('rendered output', $output);
    }

    protected function overrideServiceLocator($mockServiceLocator)
    {
        // Diese Methode ist ein Hack für das Singleton.
        // Nutzt Reflection, um den ServiceLocator zu manipulieren.

        $reflection = new ReflectionClass('Base3\Core\ServiceLocator');
        $instanceProp = $reflection->getProperty('instance');
        $instanceProp->setAccessible(true);
        $instanceProp->setValue(null, $mockServiceLocator);
    }
}

