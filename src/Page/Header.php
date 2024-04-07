<?php

namespace Base3Manager\Page;

use Api\IOutput;

class Header implements IOutput {

	private $servicelocator;

	public function __construct() {
		$this->servicelocator = \Base3\ServiceLocator::getInstance();
	}

	// Implementation of IBase

	public function getName() {
		return "header";
	}

	// Implementation of IOutput

	public function getOutput($out = "html") {

		if (!isset($_REQUEST["alias"])) die();
		$alias = str_replace("/", "", $_REQUEST["alias"]);

		$header_include_file = 'modules/' . $alias . '/header.php';
		if (file_exists($header_include_file)) {

			ob_start();
			include($header_include_file);
			$out = ob_get_contents();
			ob_end_clean();
			return $out;

		}

		$view = $this->servicelocator->get('view');
		$view->setPath(DIR_PLUGIN . 'Base3Manager');
		$view->setTemplate('Page/Header.php');
		return $view->loadTemplate();
	}

	public function getHelp() {
		return 'Help of Header' . "\n";
	}

}
