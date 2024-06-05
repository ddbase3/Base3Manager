<?php

namespace Base3Manager\Page;

use Api\IOutput;

class Index implements IOutput {

	private $servicelocator;
	private $configuration;
	private $base3manager;

	public function __construct() {
		$this->servicelocator = \Base3\ServiceLocator::getInstance();
		$this->configuration = $this->servicelocator->get('configuration');
		$this->base3manager = $this->servicelocator->get('base3manager');
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
		$view->assign('assets', $this->base3manager->getAssets());
		$view->assign('systemnavi', $this->base3manager->getSystemNavi());

		return $view->loadTemplate();
	}

	public function getHelp() {
		return 'Help of Index' . "\n";
	}

}
