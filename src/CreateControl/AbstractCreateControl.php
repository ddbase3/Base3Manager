<?php declare(strict_types=1);

namespace Base3Manager\CreateControl;

use Base3\Api\IOutput;
use Base3\Core\ServiceLocator;

abstract class AbstractCreateControl implements IOutput {

        private $view;

        public function __construct() {
                $servicelocator = ServiceLocator::getInstance();
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

