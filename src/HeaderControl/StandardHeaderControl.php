<?php

namespace Base3Manager\HeaderControl;

class StandardHeaderControl extends AbstractHeaderControl {

        // Implementation of IBase

        public function getName() {
                return "standardheadercontrol";
        }

	// Implementation of AbstractHeaderControl

	protected function getPath() {
		return DIR_PLUGIN . 'Base3Manager';
	}

	protected function getTemplate() {
		return 'HeaderControl/StandardHeaderControl.php';
	}

}

