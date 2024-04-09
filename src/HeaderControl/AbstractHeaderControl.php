<?php

namespace Base3Manager\HeaderControl;

use Api\IOutput;

abstract class AbstractHeaderControl implements IOutput {

        private $view;

        public function __construct() {
                $servicelocator = \Base3\ServiceLocator::getInstance();
                $this->view = $servicelocator->get('view');
        }

        // Implementation of IBase

        public function getName() {
                return strtolower($this->getTemplate());
        }

        // Implementation of IOutput

        public function getOutput($out = "html") {
                $this->view->setPath(DIR_PLUGIN . 'Base3Manager');
                $this->view->setTemplate('HeaderControl/' . $this->getTemplate() . '.php');
                return $this->view->loadTemplate();
        }

        public function getHelp() {
                return 'Help of ' . $this->getTemplate() . "\n";
        }

	// Abstract methods

	abstract protected function getTemplate(); 
}

