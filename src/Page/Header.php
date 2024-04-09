<?php

namespace Base3Manager\Page;

use Api\IOutput;

class Header implements IOutput {

	private $classmap;
	private $base3manager;

	public function __construct() {
		$servicelocator = \Base3\ServiceLocator::getInstance();
		$this->classmap = $servicelocator->get('classmap');
		$this->base3manager = $servicelocator->get('base3manager');
	}

	// Implementation of IBase

	public function getName() {
		return "header";
	}

	// Implementation of IOutput

	public function getOutput($out = "html") {

		if (!isset($_REQUEST["alias"])) die();
		$alias = str_replace("/", "", $_REQUEST["alias"]);

		$module = $this->base3manager->getModule($alias);
		if (!$module || !$module["header"]) return '';

		$instance = $this->classmap->getInstanceByInterfaceName("Api\\IOutput", $module["header"]);
		return $instance->getOutput();
	}

	public function getHelp() {
		return 'Help of Header' . "\n";
	}

}
