<?php declare(strict_types=1);

namespace Base3Manager\Plugin;

use Base3\Api\IPlugin;
use Base3\Api\IContainer;
use Base3\Api\ICheck;

abstract class AbstractPlugin implements IPlugin, ICheck {

	protected $container;

	public function __construct(IContainer $container) {
		$this->container = $container;
	}

	// Implementation of IBase

	public static function getName(): string {
		$fullClass = static::class;
		$parts = explode('\\', $fullClass);
		return strtolower(end($parts));
	}

	// Implementation of ICheck
	// TODO remove, implement in extension only if necessary

	public function checkDependencies(): array {
		return [];
	}

	// Private methods

	private function getClassName(): string {
		return (new \ReflectionClass($this))->getShortName();
	}
}
