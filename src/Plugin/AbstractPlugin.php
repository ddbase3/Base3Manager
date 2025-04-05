<?php declare(strict_types=1);

namespace Base3Manager\Plugin;

use Base3\Api\IPlugin;
use Base3\Api\ICheck;
use Base3\Core\ServiceLocator;

abstract class AbstractPlugin implements IPlugin, ICheck {

	protected $servicelocator;

	public function __construct() {
		$this->servicelocator = ServiceLocator::getInstance();
	}

	// Implementation of IBase

	public function getName(): string {
		return strtolower($this->getClassName());
	}

	// Implementation of ICheck

	public function checkDependencies(): array {
		return [];
	}

	// Private methods

	private function getClassName(): string {
		return (new \ReflectionClass($this))->getShortName();
	}
}
