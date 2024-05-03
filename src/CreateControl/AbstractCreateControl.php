<?php

namespace Base3Manager\CreateControl;

use Api\IOutput;

abstract class AbstractCreateControl implements IOutput {

        private $view;

        public function __construct() {
                $servicelocator = \Base3\ServiceLocator::getInstance();
                $this->view = $servicelocator->get('view');
        }

        // Implementation of IOutput

        public function getOutput($out = "html") {
                $this->view->setPath($this->getPath());
                $this->view->setTemplate($this->getTemplate());
                return $this->view->loadTemplate();
        }

        public function getHelp() {
                return 'Help of ' . $this->getTemplate() . "\n";
        }

	// Abstract methods

	abstract protected function getPath(); 
	abstract protected function getTemplate(); 
}

