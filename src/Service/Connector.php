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


namespace Base3\Base3Manager\Service;

use Base3\CoreFoundation\Api\IClassMap;
use Base3\CoreFoundation\Api\IOutput;
use Base3\Base3Manager\Service\Base3Manager;

class Connector implements IOutput {

	private $classmap; 
	private $base3manager;

	public function __construct(
		IClassMap $classmap,
		Base3Manager $base3manager
	) {
		$this->classmap = $classmap;
		$this->base3manager = $base3manager;
	}

	// Implementation of IBase

	public static function getName(): string {
		return "connector";
	}

	// Implementation of IOutput

	public function getOutput(string $out = 'html', bool $final = false): string {

		if (!isset($_REQUEST["module"])) die('No module defined.');

		$module = $this->base3manager->getModule($_REQUEST["module"]);

		if (!isset($module['connector'])) die('No connector defined for module ' . $_REQUEST["module"]);

		$instance = $this->classmap->getInstanceByInterfaceName(IOutput::class, $module['connector']);
		if ($instance == null) die('Connector not found.');

		return $instance->getOutput($out);
	}

	public function getHelp(): string {
		return 'Help of Connector' . "\n";
	}
}
