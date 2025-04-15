<?php declare(strict_types=1);

namespace Base3Manager\Page;

use Base3\Api\IClassMap;
use Base3\Api\IOutput;
use Base3Manager\Service\Base3Manager;

class Header implements IOutput {

	private $classmap;
	private $base3manager;

	public function __construct(
		IClassmap $classmap,
		Base3Manager $base3manager
	) {
		$this->classmap = $classmap;
		$this->base3manager = $base3manager;
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

		$instance = $this->classmap->getInstanceByInterfaceName(IOutput::class, $module["header"]);
		return $instance->getOutput();
	}

	public function getHelp() {
		return 'Help of Header' . "\n";
	}

}
