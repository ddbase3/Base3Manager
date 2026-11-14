<?php declare(strict_types=1);

/***********************************************************************
 * This file is part of Base3Manager for BASE3 Framework.
 *
 * Base3Manager provides manager pages and controls for BASE3.
 * It composes administration navigation, toolbars and views.
 * Extensions contribute controls through the existing plugin contracts.
 *
 * Developed by Daniel Dahme
 * Licensed under GPL-3.0
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 *
 * https://base3.de
 ***********************************************************************/


namespace Base3\Base3Manager\Plugin;

use Base3\CoreFoundation\Api\IPlugin;
use Base3\CoreFoundation\Api\IContainer;
use Base3\CoreFoundation\Api\ICheck;

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
