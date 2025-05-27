<?php declare(strict_types=1);

namespace Base3Manager\Service;

use Base3\Api\IClassMap;
use Base3\Api\IOutput;
use Base3Manager\Service\Base3Manager;

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

	public function getOutput($out = "html") {

		if (!isset($_REQUEST["module"])) die('No module defined.');

		$module = $this->base3manager->getModule($_REQUEST["module"]);

		if (!isset($module['connector'])) die('No connector defined for module ' . $_REQUEST["module"]);

		$instance = $this->classmap->getInstanceByInterfaceName(IOutput::class, $module['connector']);
		if ($instance == null) die('Connector not found.');

		return $instance->getOutput($out);
	}

	public function getHelp() {
		return 'Help of Connector' . "\n";
	}

}
