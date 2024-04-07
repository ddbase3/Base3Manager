<?php

namespace Base3Manager\Dialog;

use Api\IOutput;

class CommentsDialog implements IOutput {

	private $servicelocator;

	public function __construct() {
		$this->servicelocator = \Base3\ServiceLocator::getInstance();
	}

	// Implementation of IBase

	public function getName() {
		return "commentsdialog";
	}

	// Implementation of IOutput

	public function getOutput($out = "html") {
		$view = $this->servicelocator->get('view');
		$view->setPath(DIR_PLUGIN . 'Base3Manager');
		$view->setTemplate('Dialog/CommentsDialog.php');
		$view->assign("id", $_REQUEST["id"]);
		return $view->loadTemplate();
	}

	public function getHelp() {
		return 'Help of CommentsDialog' . "\n";
	}

}
