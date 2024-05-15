<?php

namespace Base3Manager\Page;

use Api\IOutput;

class ModuleNavi implements IOutput {

	private $servicelocator;
	private $configuration;
	private $accesscontrol;
	private $base3manager;

	public function __construct() {
		$this->servicelocator = \Base3\ServiceLocator::getInstance();
		$this->configuration = $this->servicelocator->get('configuration');
		$this->accesscontrol = $this->servicelocator->get('accesscontrol');
		$this->base3manager = $this->servicelocator->get('base3manager');
	}

	// Implementation of IBase

	public function getName() {
		return "modulenavi";
	}

	// Implementation of IOutput

	public function getOutput($out = "html") {

		// define("B3INCLUDE", true);
		// include("inc/config.php");

		session_save_path("/tmp");
		session_start();

		$cnf = $this->configuration->get('manager');
		define("SCOPE", isset($_REQUEST["scope"]) ? $_REQUEST["scope"] : $cnf['stdscope']);

		$view = $this->servicelocator->get('view');
		$view->setPath(DIR_PLUGIN . 'Base3Manager');
		$view->setTemplate('Page/ModuleNavi.php');

		$modules = $this->base3manager->getModules();
		uasort($modules, function($a, $b) {
			if ($a['order'] == $b['order']) return 0;
			return ($a['order'] < $b['order']) ? -1 : 1;
		});

                $authenticated = !!$this->accesscontrol->getUserId();
                foreach ($modules as $key => $module) {
                        $enabled = 0;
                        if (isset($module['enabled'])) {
                                if (is_array($module['enabled'])) {
                                        if (isset($module['enabled']['authenticated']) && $module['enabled']['authenticated']) {
						$enabled = 1;
						if (isset($module['enabled']['scope']) && !in_array(SCOPE, $module['enabled']['scope'])) $enabled = 0;
						if (isset($module['enabled']['noscope']) && in_array(SCOPE, $module['enabled']['noscope'])) $enabled = 0;
					}
                                } else {
                                        $enabled = $module['enabled'];
                                }
                        }
                        if (!$enabled) unset($modules[$key]);
                }

		$view->assign("modules", $modules);

		return $view->loadTemplate();
	}

	public function getHelp() {
		return 'Help of ModuleNavi' . "\n";
	}

}
