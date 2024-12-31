<?php

namespace Base3Manager;

use Api\IPlugin;
use Base3\ServiceLocator;

class Base3ManagerPlugin implements IPlugin {

	private $servicelocator;

	public function __construct() {
		$this->servicelocator = ServiceLocator::getInstance();
	}

	// Implementation of IBase

	public function getName() {
		return "base3managerplugin";
	}

	// Implementation of IPlugin

	public function init() {
		$this->servicelocator

			->set(
				$this->getName(),
				$this,
				ServiceLocator::SHARED)

			->set(
				'view',
				function() {
					return new \Base3\MvcView;
				})

			->set(
				'base3manager',
				new \Base3Manager\Service\Base3Manager,
				ServiceLocator::SHARED);
	}

}
