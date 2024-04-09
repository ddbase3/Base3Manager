<?php

namespace Base3Manager\Page;

use Api\IOutput;

class Subnavi implements IOutput {

	private $servicelocator;
	private $accesscontrol;
	private $base3manager;

	public function __construct() {
		$this->servicelocator = \Base3\ServiceLocator::getInstance();
		$this->accesscontrol = $this->servicelocator->get('accesscontrol');
		$this->base3manager = $this->servicelocator->get('base3manager');
	}

	// Implementation of IBase

	public function getName() {
		return "subnavi";
	}

	// Implementation of IOutput

	public function getOutput($out = "html") {

                if (!isset($_REQUEST["alias"])) die();
                $alias = str_replace("/", "", $_REQUEST["alias"]);

                $module = $this->base3manager->getModule($alias);
                if (!$module || !$module["subnavi"]) return '';

		define("B3INCLUDE", true);
		include("inc/config.php");

                $view = $this->servicelocator->get('view');
                $view->setPath(DIR_PLUGIN . 'Base3Manager');
                $view->setTemplate('Page/Subnavi.php');
                $view->assign("alias", $alias);
		$view->assign("module", $module);

                $subnavi = $module['subnavi'];
                uasort($subnavi, function($a, $b) {
                        if ($a['order'] == $b['order']) return 0;
                        return ($a['order'] < $b['order']) ? -1 : 1;
                });

                $authenticated = !!$this->accesscontrol->getUserId();
                foreach ($subnavi as $key => $sub) {
                        $enabled = 0;
                        if (isset($sub['enabled'])) {
                                if (is_array($sub['enabled'])) {
                                        if (isset($sub['enabled']['authenticated']) && $sub['enabled']['authenticated']) $enabled = 1;
                                } else {
                                        $enabled = $sub['enabled'];
                                }
                        }
                        if (!$enabled) unset($subnavi[$key]);
                }

		$view->assign("subnavi", $subnavi);

		return $view->loadTemplate();

/*
		define("B3INCLUDE", true);
		include("inc/config.php");
		if (!isset($_REQUEST["alias"]) || !strlen($_REQUEST["alias"])) die();
		$alias = str_replace("/", "", $_REQUEST["alias"]);

		// Instanz des Moduls laden
		include("modules/".$alias."/module.php");
		$class = strtoupper(substr($alias, 0, 1)).substr($alias, 1).'Module';
		$module = new $class();

		// Array mit Instanzen aller subnavi des Moduls
		$subnavi = array();

		$dir = "modules/".$alias."/subnavi";
		if (is_dir($dir)) {
			$handle = opendir($dir);
			while ($file = readdir($handle)) {
				if (in_array($file, array(".", ".."))) continue;
				include("modules/".$alias."/subnavi/".$file."/subnavi.php");

				$class = strtoupper(substr($alias, 0, 1)).substr($alias, 1).strtoupper(substr($file, 0, 1)).substr($file, 1).'Subnavi';
				$sub = new $class();

				if (!$sub->isEnabled()) continue;
				$subnavi[$file] = $sub;
			}
			closedir($handle);
		}

		uasort($subnavi, function($a, $b) {
			if ($a->getOrder() == $b->getOrder()) return 0;
			return ($a->getOrder() < $b->getOrder()) ? -1 : 1;
		});

		$view = $this->servicelocator->get('view');
		$view->setPath(DIR_PLUGIN . 'Base3Manager');
		$view->setTemplate('Page/Subnavi.php');
		$view->assign("alias", $alias);
		$view->assign("module", $module);
		$view->assign("subnavi", $subnavi);
		return $view->loadTemplate();
*/

	}

	public function getHelp() {
		return 'Help of Subnavi' . "\n";
	}

}


