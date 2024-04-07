<?php

namespace Base3Manager\Page;

use Api\IOutput;

class ModuleNavi implements IOutput {

	private $servicelocator;
	private $configuration;

	public function __construct() {
		$this->servicelocator = \Base3\ServiceLocator::getInstance();
		$this->configuration = $this->servicelocator->get('configuration');
	}

	// Implementation of IBase

	public function getName() {
		return "modulenavi";
	}

	// Implementation of IOutput

	public function getOutput($out = "html") {

		define("B3INCLUDE", true);
		include("inc/config.php");
		include("inc/init.php");

		$cnf = $this->configuration->get('manager');
		define("SCOPE", isset($_REQUEST["scope"]) ? $_REQUEST["scope"] : $cnf['stdscope']);

		$view = $this->servicelocator->get('view');
		$view->setPath(DIR_PLUGIN . 'Base3Manager');
		$view->setTemplate('Page/ModuleNavi.php');

		// Array mit Instanzen aller Bereiche (typenavi-Menüpunkte) 
		$modules = array();

		$handle = opendir("modules/");

		while ($file = readdir($handle)) {
			if (in_array($file, array(".", ".."))) continue;
			$filename = "modules/".$file."/module.php";
			if (!file_exists($filename)) continue;
			include($filename);

			$class = strtoupper(substr($file, 0, 1)).substr($file, 1).'Module';
			$module = new $class();
			if (!$module->isEnabled()) continue;
			$modules[$file] = $module;
		}

		closedir($handle);

		uasort($modules, function($a, $b) {
			if ($a->getOrder() == $b->getOrder()) return 0;
			return ($a->getOrder() < $b->getOrder()) ? -1 : 1;
		});
		$view->assign("modules", $modules);

		return $view->loadTemplate();
	}

	public function getHelp() {
		return 'Help of ModuleNavi' . "\n";
	}

}
