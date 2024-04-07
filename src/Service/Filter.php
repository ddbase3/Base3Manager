<?php

namespace Base3Manager\Dialog;

use Api\IOutput;

class Filter implements IOutput {

	// Implementation of IBase

	public function getName() {
		return "filter";
	}

	// Implementation of IOutput

	public function getOutput($out = "html") {

		$module = $_REQUEST["module"];
		$unset = isset($_REQUEST["unset"]);

		if ($unset) {
			$_SESSION["filter"][$module] = array();
			exit;
		}

		$req = array_merge($_GET, $_POST);
		foreach ($req as $key => $value) {
			if (in_array($key, array("module", "unset"))) continue;
			$_SESSION["filter"][$module][$key] = $value;
		}

		return "done";
	}

	public function getHelp() {
		return 'Help of Filter' . "\n";
	}

}
