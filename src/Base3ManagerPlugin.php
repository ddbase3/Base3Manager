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

		$contents = file_get_contents('inc/config.json');
		$this->servicelocator->set('manager', json_decode($contents, true), true);
	}

}
