<?php declare(strict_types=1);

namespace Base3Manager\CreateControl;

use Base3\Api\IMvcView;
use Base3\Api\IOutput;

abstract class AbstractCreateControl implements IOutput {

        private $view;

        public function __construct(IMvcView $view) {
                $this->view = $view;
        }

        // Implementation of IOutput

	public function getOutput(string $out = 'html', bool $final = false): string {
                $this->view->setPath($this->getPath());
                $this->view->setTemplate($this->getTemplate());
                return $this->view->loadTemplate();
        }

        public function getHelp(): string {
                return 'Help of ' . $this->getTemplate() . "\n";
        }

	// Abstract methods

	abstract protected function getPath(); 
	abstract protected function getTemplate(); 
}
