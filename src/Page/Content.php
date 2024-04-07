<?php

namespace Base3Manager\Page;

use Api\IOutput;

class Content implements IOutput {

	// Implementation of IBase

	public function getName() {
		return "content";
	}

	// Implementation of IOutput

	public function getOutput($out = "html") {

		define("B3INCLUDE", true);
		include("inc/config.php");
		include("inc/init.php");

		if (!isset($_REQUEST["alias"])) die();
		$alias = str_replace("/", "", $_REQUEST["alias"]);

		$content_include_file = "";
		if (isset($_REQUEST["tabalias"])) {
			$tabalias = str_replace("/", "", $_REQUEST["tabalias"]);
			$content_include_file = "modules/".$alias."/tabs/".$tabalias."/content.php";
		}
		if (isset($_REQUEST["subnavialias"])) {
			$subnavialias = str_replace("/", "", $_REQUEST["subnavialias"]);
			$content_include_file = "modules/".$alias."/subnavi/".$subnavialias."/content.php";
		}

		if (!file_exists($content_include_file)) die();

		include("modules/".$alias."/module.php");
		$class = strtoupper(substr($alias, 0, 1)).substr($alias, 1).'Module';
		$module = new $class();

		ob_start();
		include($content_include_file);
		$out = ob_get_contents();
		ob_end_clean();

		return $out;
	}

	public function getHelp() {
		return 'Help of Content' . "\n";
	}

}




