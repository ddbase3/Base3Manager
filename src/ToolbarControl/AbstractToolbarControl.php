<?php declare(strict_types=1);

/***********************************************************************
 * This file is part of Base3Manager for BASE3 Framework.
 *
 * Base3Manager provides manager pages and controls for BASE3.
 * It composes administration navigation, toolbars and views.
 * Extensions contribute controls through the existing plugin contracts.
 *
 * Developed by Daniel Dahme
 * Licensed under GPL-3.0
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 *
 * https://base3.de
 ***********************************************************************/


namespace Base3\Base3Manager\ToolbarControl;

use Base3\CoreFoundation\Api\IMvcView;
use Base3\CoreFoundation\Api\IOutput;

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

	public function getOutput(string $out = 'html', bool $final = false): string {
		$this->view->setPath($this->getPath());
		$this->view->setTemplate($this->getTemplate());
                $this->fillView();
		$this->view->assign('action', $this->tool['tool']);
		$this->view->assign('params', isset($this->tool['params']) ? $this->tool['params'] : array());
		return $this->view->loadTemplate();
	}

        // Implementation of IOutput

        public function getHelp(): string {
                return 'Help of ' . $this->getTemplate() . "\n";
        }

	// Abstract methods

        protected function fillView() {}
	abstract protected function getPath();
	abstract protected function getTemplate();
}
