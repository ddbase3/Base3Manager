<?php

namespace Base3Manager\Page;

use Api\IOutput;

class Tabs implements IOutput {

	private $servicelocator;
	private $configuration;

	public function __construct() {
		$this->servicelocator = \Base3\ServiceLocator::getInstance();
		$this->configuration = $this->servicelocator->get('configuration');
	}

	// Implementation of IBase

	public function getName() {
		return "tabs";
	}

	// Implementation of IOutput

	public function getOutput($out = "html") {

		define("B3INCLUDE", 1);
		include("inc/config.php");
		include("inc/init.php");

		if (!isset($_REQUEST["alias"])) die();
		$alias = str_replace("/", "", $_REQUEST["alias"]);

		$view = $this->servicelocator->get('view');
		$view->setPath(DIR_PLUGIN . 'Base3Manager');
		$view->setTemplate('Page/Tabs.php');
		$view->assign("alias", $alias);

		// Array mit Instanzen aller Tabs des Moduls
		$tabs = array();

		$dir = "modules/" . $alias . "/tabs/";
		if (!is_dir($dir)) die();
		$handle = opendir($dir);

		while ($file = readdir($handle)) {
			if (in_array($file, array(".", ".."))) continue;
			include($dir . $file . "/tab.php");

			$class = strtoupper(substr($alias, 0, 1)).substr($alias, 1).strtoupper(substr($file, 0, 1)).substr($file, 1).'Tab';
			$tab = new $class();
			if (!$tab->isEnabled()) continue;
			$tabs[$file] = $tab;
		}

		closedir($handle);

		uasort($tabs, function($a, $b) {
			if ($a->getOrder() == $b->getOrder()) return 0;
			return ($a->getOrder() < $b->getOrder()) ? -1 : 1;
		});
		$view->assign("tabs", $tabs);

		return $view->loadTemplate();
	}

	public function getHelp() {
		return 'Help of Tabs' . "\n";
	}

}
