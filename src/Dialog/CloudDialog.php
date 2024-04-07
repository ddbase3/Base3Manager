<?php

namespace Base3Manager\Dialog;

use Api\IOutput;

class CloudDialog implements IOutput {

	private $servicelocator;
	private $configuration;

	public function __construct() {
		$this->servicelocator = \Base3\ServiceLocator::getInstance();
		$this->configuration = $this->servicelocator->get('configuration');
	}

	// Implementation of IBase

	public function getName() {
		return "clouddialog";
	}

	// Implementation of IOutput

	public function getOutput($out = "html") {
		$view = $this->servicelocator->get('view');
		$view->setPath(DIR_PLUGIN . 'Base3Manager');
		$view->setTemplate('Dialog/CloudDialog.php');
		$view->assign("id", intval($_REQUEST["id"]));

		$cnf = $this->configuration->get('manager');
		$view->assign("url", $cnf['cloudurl']);

		return $view->loadTemplate();
	}

	public function getHelp() {
		return 'Help of CloudDialog' . "\n";
	}

}
