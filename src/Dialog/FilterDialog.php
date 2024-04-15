<?php

namespace Base3Manager\Dialog;

use Api\IOutput;

class FilterDialog implements IOutput {

	private $servicelocator;
	private $classmap;
	private $base3manager;

	public function __construct() {
		$this->servicelocator = \Base3\ServiceLocator::getInstance();
		$this->classmap = $this->servicelocator->get('classmap');
		$this->base3manager = $this->servicelocator->get('base3manager');
	}

	// Implementation of IBase

	public function getName() {
		return "filterdialog";
	}

	// Implementation of IOutput

	public function getOutput($out = "html") {

		$alias = str_replace(array('/', '.', '?'), '', $_REQUEST["module"]);
		$module = $this->base3manager->getModule($alias);
		if (!$module || !isset($module['filter'])) die();

		$view = $this->servicelocator->get('view');
		$view->setPath(DIR_PLUGIN . 'Base3Manager');
		$view->setTemplate('Dialog/FilterDialog.php');

		$view->assign('alias', $alias);

		$instance = $this->classmap->getInstanceByInterfaceName("Api\\IOutput", $module['filter']);
		if ($instance == null) die();
		$view->assign('filtercontrol', $instance->getOutput());

		return $view->loadTemplate();
	}

	public function getHelp() {
		return 'Help of FilterDialog' . "\n";
	}

}
