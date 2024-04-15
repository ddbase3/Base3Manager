<?php

namespace Base3Manager\FilterControl;

class DateFilterControl extends AbstractFilterControl {

        // Implementation of IBase

        public function getName() {
                return "datefiltercontrol";
        }

	// Implementation of AbstractListControl

	protected function getPath() {
		return DIR_PLUGIN . 'Base3Manager';
	}

	protected function getTemplate() {
		return 'FilterControl/DateFilterControl.php';
	}

}

