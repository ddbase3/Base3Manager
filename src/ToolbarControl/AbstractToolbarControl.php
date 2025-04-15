<?php declare(strict_types=1);

namespace Base3Manager\ToolbarControl;

use Base3\Api\IMvcView;
use Base3\Api\IOutput;

abstract class AbstractToolbarControl implements IOutput {

        protected $view;

	protected $alias;
	protected $tool;

        public function __construct(IMvcView $view) {
		$this->view = $view;
        }

	public function setAlias($alias) {
		$this->alias = $alias;
	}

	public function setTool($tool) {
		$this->tool = $tool;
	}

        // Implementation of IOutput

	public function getOutput($out = "html") {
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

