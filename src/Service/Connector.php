<?php declare(strict_types=1);

namespace Base3Manager\Service;

use Api\IOutput;

class Connector implements IOutput {

	private $servicelocator;
	private $classmap; 
	private $base3manager;

	public function __construct() {
		$this->servicelocator = \Base3\ServiceLocator::getInstance();
		$this->classmap = $this->servicelocator->get('classmap');
		$this->base3manager = $this->servicelocator->get('base3manager');
	}

	// Implementation of IBase

	public function getName() {
		return "connector";
	}

	// Implementation of IOutput

	public function getOutput($out = "html") {

		if (!isset($_REQUEST["module"])) die('No module defined.');

		$module = $this->base3manager->getModule($_REQUEST["module"]);

		if (!isset($module['connector'])) die('No connector defined for module ' . $_REQUEST["module"]);

		$instance = $this->classmap->getInstanceByInterfaceName("Api\\IOutput", $module['connector']);
		if ($instance == null) die('Connector not found.');

		return $instance->getOutput($out);
	}

	public function getHelp() {
		return 'Help of Connector' . "\n";
	}

}
