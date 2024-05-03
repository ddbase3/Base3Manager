<?php

namespace Base3Manager\ContentControl;

use Api\IOutput;

abstract class AbstractContentControl implements IOutput {

        private $view;
	private $base3manager;

        public function __construct() {
                $servicelocator = \Base3\ServiceLocator::getInstance();
                $this->view = $servicelocator->get('view');
		$this->base3manager = $servicelocator->get('base3manager');
        }

        // Implementation of IOutput

        public function getOutput($out = "html") {
                $this->view->setPath($this->getPath());
                $this->view->setTemplate($this->getTemplate());

		$alias = str_replace(array('/', '.', '?'), '', $_REQUEST['alias']);
		$this->view->assign('alias', $alias);

		$module = $this->base3manager->getModule($alias);
                $this->view->assign('module', $module);

		$config = $this->base3manager->getConfig();
		$this->view->assign('config', $config);

                return $this->view->loadTemplate();
        }

        public function getHelp() {
                return 'Help of ' . $this->getTemplate() . "\n";
        }

	// Abstract methods

	abstract protected function getPath(); 
	abstract protected function getTemplate(); 
}

