<?php declare(strict_types=1);

namespace Base3Manager\ToolbarControl;

use Base3\Api\IMvcView;
use Base3\Language\Api\ILanguage;

class SwitchLanguageToolbarControl extends AbstractToolbarControl {

	protected $language;

	public function __construct(IMvcView $view, ILanguage $language) {
		parent::__construct($view);
		$this->language = $language;
	}

        // Implementation of IBase

        public function getName(): string {
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

