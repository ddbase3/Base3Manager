<?php declare(strict_types=1);

namespace Base3Manager\ListControl;

use Base3\Api\IMvcView;
use Base3\Api\IOutput;

abstract class AbstractListControl implements IOutput {

        private $view;

        public function __construct(IMvcView $view) {
                $this->view = $view;
        }

        // Implementation of IOutput

        public function getOutput($out = "html") {

		if (!isset($_REQUEST["alias"])) die();
		$alias = str_replace("/", "", $_REQUEST["alias"]);

                $this->view->setPath($this->getPath());
                $this->view->setTemplate($this->getTemplate());
		$this->view->assign("alias", $alias);
                return $this->view->loadTemplate();
        }

        public function getHelp() {
                return 'Help of ' . $this->getTemplate() . "\n";
        }

	// Abstract methods

	abstract protected function getPath(); 
	abstract protected function getTemplate(); 
}

