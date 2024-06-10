<?php

namespace Base3Manager\ToolbarControl;

class OpenModalToolbarControl extends AbstractToolbarControl {

        // Implementation of IBase

	public function getName() {
		return "openmodaltoolbarcontrol";
	}

        // Implementation of AbstractContentControl

        protected function getPath() {
                return DIR_PLUGIN . 'Base3Manager';
        }

        protected function getTemplate() {
                return 'ToolbarControl/OpenModalToolbarControl.php';
        }
}

