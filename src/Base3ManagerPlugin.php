<?php

namespace Base3Manager;

use Api\IPlugin;

class Base3ManagerPlugin implements IPlugin {

	// Implementation of IBase

	public function getName() {
		return "base3managerplugin";
	}

	// Implementation of IPlugin

	public function init() {
		$servicelocator = \Base3\ServiceLocator::getInstance()
			->set($this->getName(), $this, true)
			->set('view', function() { return new \Base3\MvcView; })
			;
	}

}
