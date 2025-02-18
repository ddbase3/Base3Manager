<?php declare(strict_types=1);

namespace Base3Manager\Page;

use Api\IOutput;
use Base3\ServiceLocator;

class Index implements IOutput {

	private $configuration;
	private $language;
	private $base3manager;
	private $view;

	public function __construct() {
		$servicelocator = ServiceLocator::getInstance();
		$this->configuration = $servicelocator->get('configuration');
		$this->language = $servicelocator->get('language');
		$this->base3manager = $servicelocator->get('base3manager');
		$this->view = $servicelocator->get('view');
	}

	// Implementation of IBase

	public function getName() {
		return "index";
	}

	// Implementation of IOutput

	public function getOutput($out = "html") {

		$cnf = $this->configuration->get('manager');

		$this->view->setPath(DIR_PLUGIN . 'Base3Manager');
		$this->view->setTemplate('Page/Index.php');

		$this->view->assign('layout', $cnf['layout']);
		$this->view->assign('language', $this->language->getLanguage());
		$this->view->assign('assets', $this->base3manager->getAssets());
		$this->view->assign('systemnavi', $this->base3manager->getSystemNavi());

		return $this->view->loadTemplate();
	}

	public function getHelp() {
		return 'Help of Index' . "\n";
	}

}
