<?php

namespace Base3Manager\Page;

use Api\IOutput;

class Content implements IOutput {

        private $servicelocator;
        private $classmap;
        private $base3manager;

        public function __construct() {
                $this->servicelocator = \Base3\ServiceLocator::getInstance();
                $this->classmap = $this->servicelocator->get('classmap');
                $this->base3manager = $this->servicelocator->get('base3manager');
        }

	// Implementation of IBase

	public function getName() {
		return "content";
	}

	// Implementation of IOutput

	public function getOutput($out = "html") {

		define("B3ROOT", '');
		define("B3INCLUDE", true);
		include("inc/config.php");

		session_save_path("/tmp");
		session_start();

		if (!isset($_REQUEST["alias"])) die();
		$alias = str_replace("/", "", $_REQUEST["alias"]);

		$oldstyle = 0;
		$content_include_file = "";  // TODO remove old style
		$control = null;
		$module = $this->base3manager->getModule($alias);
		if (isset($_REQUEST["tabalias"]) && isset($module['tabs'])) {
			foreach ($module['tabs'] as $tab) {
				if ($tab['tab'] != $_REQUEST["tabalias"]) continue;
				if (isset($tab['content'])) {
					$control = $tab['content'];
				} else {
					// TODO remove old style
					$oldstyle = 1;
					$tabalias = str_replace("/", "", $_REQUEST["tabalias"]);
					$content_include_file = "modules/".$alias."/tabs/".$tabalias."/content.php";
				}
				break;
			}
		}
		if (isset($_REQUEST["subnavialias"]) && isset($module['subnavi'])) {
			foreach ($module['subnavi'] as $subnavi) {
				if ($subnavi['subnavi'] != $_REQUEST["subnavialias"]) continue;
				if (isset($subnavi['content'])) {
					$control = $subnavi['content'];
				} else {
					// TODO remove old style
					$oldstyle = 1;
					$subnavialias = str_replace("/", "", $_REQUEST["subnavialias"]);
					$content_include_file = "modules/".$alias."/subnavi/".$subnavialias."/content.php";
				}
				break;
			}
		}

/*
		$content_include_file = "";
		if (isset($_REQUEST["tabalias"])) {
			$tabalias = str_replace("/", "", $_REQUEST["tabalias"]);
			$content_include_file = "modules/".$alias."/tabs/".$tabalias."/content.php";
		}
		if (isset($_REQUEST["subnavialias"])) {
			$subnavialias = str_replace("/", "", $_REQUEST["subnavialias"]);
			$content_include_file = "modules/".$alias."/subnavi/".$subnavialias."/content.php";
		}
*/

		if ($oldstyle) {
			if (!file_exists($content_include_file)) die();

			include("modules/".$alias."/module.php");
			$class = strtoupper(substr($alias, 0, 1)).substr($alias, 1).'Module';
			$module = new $class();

			ob_start();
			include($content_include_file);
			$out = ob_get_contents();
			ob_end_clean();
		} else {
			if ($control == null) die();
			$instance = $this->classmap->getInstanceByInterfaceName("Api\\IOutput", $control);
			if ($instance == null) die();
			return $instance->getOutput();
		}

		return $out;
	}

	public function getHelp() {
		return 'Help of Content' . "\n";
	}

}




