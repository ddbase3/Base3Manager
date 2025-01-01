<?php declare(strict_types=1);

namespace Base3Manager\FilterControl;

use Api\IOutput;

abstract class AbstractFilterControl implements IOutput {

        private $view;

        public function __construct() {
                $servicelocator = \Base3\ServiceLocator::getInstance();
                $this->view = $servicelocator->get('view');
        }

        // Implementation of IOutput

        public function getOutput($out = "html") {

                $this->view->setPath($this->getPath());
                $this->view->setTemplate($this->getTemplate());

		$alias = str_replace(array('/', '.', '?'), '', $_REQUEST["module"]);
                $this->view->assign('alias', $alias);

                return $this->view->loadTemplate();
        }

        public function getHelp() {
                return 'Help of ' . $this->getTemplate() . "\n";
        }

	// Abstract methods

	abstract protected function getPath(); 
	abstract protected function getTemplate(); 
}

