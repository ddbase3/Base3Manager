<?php declare(strict_types=1);

namespace Base3Manager\ContentControl;

use Base3\Api\IMvcView;
use Base3\Api\IOutput;
use Base3Manager\Service\Base3Manager;

abstract class AbstractContentControl implements IOutput {

        public function __construct(
		protected IMvcView $view,
		protected Base3Manager $base3manager
	) {}

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

