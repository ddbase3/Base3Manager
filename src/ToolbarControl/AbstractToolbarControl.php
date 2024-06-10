<?php

namespace Base3Manager\ToolbarControl;

use Api\IOutput;

abstract class AbstractToolbarControl implements IOutput {

        private $view;

	protected $tool;

        public function __construct() {
                $servicelocator = \Base3\ServiceLocator::getInstance();
                $this->view = $servicelocator->get('view');
        }

	public function setTool($tool) {
		$this->tool = $tool;
	}

        // Implementation of IOutput

	public function getOutput($out = "html") {
		$this->view->setPath($this->getPath());
		$this->view->setTemplate($this->getTemplate());
		$this->view->assign('action', $this->tool['tool']);
		$this->view->assign('params', $this->tool['params']);
		return $this->view->loadTemplate();
	}

        // Implementation of IOutput

        public function getHelp() {
                return 'Help of ' . $this->getTemplate() . "\n";
        }

	// Abstract methods

	abstract protected function getPath();
	abstract protected function getTemplate();
}

