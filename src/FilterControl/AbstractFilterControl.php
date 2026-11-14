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


namespace Base3\Base3Manager\FilterControl;

use Base3\CoreFoundation\Api\IMvcView;
use Base3\CoreFoundation\Api\IOutput;

abstract class AbstractFilterControl implements IOutput {

        private $view;

        public function __construct(IMvcView $view) {
                $this->view = $view;
        }

        // Implementation of IOutput

	public function getOutput(string $out = 'html', bool $final = false): string {

                $this->view->setPath($this->getPath());
                $this->view->setTemplate($this->getTemplate());

		$alias = str_replace(array('/', '.', '?'), '', $_REQUEST["module"]);
                $this->view->assign('alias', $alias);

                return $this->view->loadTemplate();
        }

        public function getHelp(): string {
                return 'Help of ' . $this->getTemplate() . "\n";
        }

	// Abstract methods

	abstract protected function getPath(); 
	abstract protected function getTemplate(); 
}
