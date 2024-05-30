<?php

namespace Base3Manager;

use Api\IPlugin;

class Base3ManagerPlugin implements IPlugin {

	private $servicelocator;

	public function __construct() {
		$this->servicelocator = \Base3\ServiceLocator::getInstance();
	}

	// Implementation of IBase

	public function getName() {
		return "base3managerplugin";
	}

	// Implementation of IPlugin

	public function init() {
		$this->servicelocator
			->set($this->getName(), $this, true)
			->set('view', function() { return new \Base3\MvcView; })
			;

		$this->servicelocator->set('base3manager', new \Base3Manager\Service\Base3Manager, true);
	}

}
