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
use Base3\CoreFoundation\Language\Api\ILanguage;

class SwitchLanguageToolbarControl extends AbstractToolbarControl {

	protected $language;

	public function __construct(IMvcView $view, ILanguage $language) {
		parent::__construct($view);
		$this->language = $language;
	}

        // Implementation of IBase

        public static function getName(): string {
                return "switchlanguagetoolbarcontrol";
        }

        // Implementation of AbstractToolbarControl

        protected function getPath(): string {
                return DIR_PLUGIN . 'Base3Manager';
        }

        protected function getTemplate(): string {
                return 'ToolbarControl/SwitchLanguageToolbarControl.php';
        }

        protected function fillView() {
                $this->view->assign('languages', $this->language->getLanguages());
	}
}

