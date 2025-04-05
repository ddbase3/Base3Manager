<?php declare(strict_types=1);

namespace Base3Manager\ToolbarControl;

use Base3\Api\IOutput;
use Base3\Core\ServiceLocator;

abstract class AbstractToolbarControl implements IOutput {

        protected $servicelocator;
        protected $view;

	protected $alias;
	protected $tool;

        public function __construct() {
                $this->servicelocator = ServiceLocator::getInstance();
        }

	public function setAlias($alias) {
		$this->alias = $alias;
	}

	public function setTool($tool) {
		$this->tool = $tool;
	}

        // Implementation of IOutput

	public function getOutput($out = "html") {
                $this->view = $this->servicelocator->get('view');
		$this->view->setPath($this->getPath());
		$this->view->setTemplate($this->getTemplate());
                $this->fillView();
		$this->view->assign('action', $this->tool['tool']);
		$this->view->assign('params', isset($this->tool['params']) ? $this->tool['params'] : array());
		return $this->view->loadTemplate();
	}

        // Implementation of IOutput

        public function getHelp() {
                return 'Help of ' . $this->getTemplate() . "\n";
        }

	// Abstract methods

        protected function fillView() {}
	abstract protected function getPath();
	abstract protected function getTemplate();
}

