<?php

namespace Base3Manager\Dialog;

use Api\IOutput;

class CopyDialog implements IOutput {

	private $servicelocator;

	public function __construct() {
		$this->servicelocator = \Base3\ServiceLocator::getInstance();
	}

	// Implementation of IBase

	public function getName() {
		return "copydialog";
	}

	// Implementation of IOutput

	public function getOutput($out = "html") {
		$view = $this->servicelocator->get('view');
		$view->setPath(DIR_PLUGIN . 'Base3Manager');
		$view->setTemplate('Dialog/CopyDialog.php');
		return $view->loadTemplate();
	}

	public function getHelp() {
		return 'Help of CopyDialog' . "\n";
	}

}
