<?php

namespace Base3Manager\Dialog;

use Api\IOutput;

class CreateDialog implements IOutput {

	private $servicelocator;

	public function __construct() {
		$this->servicelocator = \Base3\ServiceLocator::getInstance();
	}

	// Implementation of IBase

	public function getName() {
		return "createdialog";
	}

	// Implementation of IOutput

	public function getOutput($out = "html") {
		$view = $this->servicelocator->get('view');
		$view->setPath(DIR_PLUGIN . 'Base3Manager');
		$view->setTemplate('Dialog/CreateDialog.php');
		$view->assign("module", str_replace(array('/', '.', '?'), '', $_REQUEST["module"]));
		return $view->loadTemplate();
	}

	public function getHelp() {
		return 'Help of CreateDialog' . "\n";
	}

}
