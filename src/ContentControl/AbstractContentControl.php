<?php declare(strict_types=1);

namespace Base3Manager\ContentControl;

use Api\IOutput;
use Base3\ServiceLocator;

abstract class AbstractContentControl implements IOutput {

        protected $servicelocator;
        protected $view;
	protected $base3manager;

        public function __construct() {
                $this->servicelocator = ServiceLocator::getInstance();
                $this->view = $this->servicelocator->get('view');
		$this->base3manager = $this->servicelocator->get('base3manager');
        }

        // Implementation of IOutput

        public function getOutput($out = "html") {
                $this->view->setPath($this->getPath());
                $this->view->setTemplate($this->getTemplate());

		$this->fillView();

		$alias = str_replace(array('/', '.', '?'), '', $_REQUEST['alias']);
		$this->view->assign('alias', $alias);

		$module = $this->base3manager->getModule($alias);
                $this->view->assign('module', $module);

                return $this->view->loadTemplate();
        }

        public function getHelp() {
                return 'Help of ' . $this->getTemplate() . "\n";
        }

	// Abstract methods

	protected function fillView() {}
	abstract protected function getPath(); 
	abstract protected function getTemplate(); 
}

