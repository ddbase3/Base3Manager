<?php declare(strict_types=1);

namespace Base3Manager\Page;

use Api\IOutput;

class Toolbar implements IOutput {

	private $servicelocator;
	private $configuration;
	private $classmap;
	private $accesscontrol;
	private $base3manager;

	public function __construct() {
		$this->servicelocator = \Base3\ServiceLocator::getInstance();
		$this->configuration = $this->servicelocator->get('configuration');
		$this->classmap = $this->servicelocator->get('classmap');
		$this->accesscontrol = $this->servicelocator->get('accesscontrol');
		$this->base3manager = $this->servicelocator->get('base3manager');
	}

	// Implementation of IBase

	public function getName() {
		return "toolbar";
	}

	// Implementation of IOutput

	public function getOutput($out = "html") {

                if (!isset($_REQUEST["alias"])) die();
                $alias = str_replace("/", "", $_REQUEST["alias"]);

                $module = $this->base3manager->getModule($alias);
                if (!$module) return '';

		if (!isset($module['list'])) $module['list'] = 'standardlistcontrol';

                $view = $this->servicelocator->get('view');
                $view->setPath(DIR_PLUGIN . 'Base3Manager');
                $view->setTemplate('Page/Toolbar.php');
                $view->assign("alias", $alias);
		$view->assign("module", $module);

		$manager = $this->configuration->get('manager');
                $view->assign("manager", $manager);

		// toolbar

		$toolbar = array();

		$toolbarcontrols = $this->base3manager->getToolbarControls();
		if (isset($module['toolbar'])) foreach ($module['toolbar'] as $toolgroup) {
			$group = array();
			foreach ($toolgroup as $tool) {
				foreach ($toolbarcontrols as $control) {
					if ($control['tool'] != $tool) continue;

					$instance = $this->classmap->getInstanceByInterfaceName("Api\\IOutput", $control['control']);
					if ($instance == null) continue;
					$instance->setAlias($alias);
					$instance->setTool($control);
					$group[] = $instance->getOutput();
				}
			}
			$toolbar[] = $group;
		}

		$view->assign("toolbar", $toolbar);

		return $view->loadTemplate();
	}

	public function getHelp() {
		return 'Help of Toolbar' . "\n";
	}

}

