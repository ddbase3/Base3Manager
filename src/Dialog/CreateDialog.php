<?php

namespace Base3Manager\Dialog;

use Api\IOutput;

class CreateDialog implements IOutput {

	private $servicelocator;
	private $classmap;
	private $configuration;
	private $base3manager;

	public function __construct() {
		$this->servicelocator = \Base3\ServiceLocator::getInstance();
		$this->classmap = $this->servicelocator->get('classmap');
		$this->configuration = $this->servicelocator->get('configuration');
		$this->base3manager = $this->servicelocator->get('base3manager');
	}

	// Implementation of IBase

	public function getName() {
		return "createdialog";
	}

	// Implementation of IOutput

	public function getOutput($out = "html") {

		$alias = str_replace(array('/', '.', '?'), '', $_REQUEST["module"]);
		$module = $this->base3manager->getModule($alias);
		if (!$module || !isset($module['create']) || !isset($module['create']['control'])) die();

		$view = $this->servicelocator->get('view');
		$view->setPath(DIR_PLUGIN . 'Base3Manager');
		$view->setTemplate('Dialog/CreateDialog.php');

		$instance = $this->classmap->getInstanceByInterfaceName("Api\\IOutput", $module['create']['control']);
		if ($instance == null) die();
		$view->assign('createcontrol', $instance->getOutput());

		$view->assign('accessstdallusers', $this->configuration->get('manager')['accessstdallusers']);

		return $view->loadTemplate();
	}

	public function getHelp() {
		return 'Help of CreateDialog' . "\n";
	}

}
