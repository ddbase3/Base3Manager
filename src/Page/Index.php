<?php

namespace Base3Manager\Page;

use Api\IOutput;

class Index implements IOutput {

	private $servicelocator;
	private $configuration;

	public function __construct() {
		$this->servicelocator = \Base3\ServiceLocator::getInstance();
		$this->configuration = $this->servicelocator->get('configuration');
	}

	// Implementation of IBase

	public function getName() {
		return "index";
	}

	// Implementation of IOutput

	public function getOutput($out = "html") {

		$cnf = $this->configuration->get('manager');

		$view = $this->servicelocator->get('view');
		$view->setPath(DIR_PLUGIN . 'Base3Manager');
		$view->setTemplate('Page/Index.php');
		$view->assign('layout', $cnf['layout']);
		return $view->loadTemplate();
	}

	public function getHelp() {
		return 'Help of Index' . "\n";
	}

}
