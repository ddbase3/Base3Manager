<?php

namespace Base3Manager\FilterControl;

class StandardFilterControl extends AbstractFilterControl {

        // Implementation of IBase

        public function getName() {
                return "standardfiltercontrol";
        }

	// Implementation of AbstractListControl

	protected function getPath() {
		return DIR_PLUGIN . 'Base3Manager';
	}

	protected function getTemplate() {
		return 'FilterControl/StandardFilterControl.php';
	}

}

