<?php

namespace Base3Manager\CreateControl;

class ProjectCreateControl extends AbstractCreateControl {

        // Implementation of IBase

        public function getName() {
                return "projectcreatecontrol";
        }

	// Implementation of AbstractListControl

	protected function getPath() {
		return DIR_PLUGIN . 'Base3Manager';
	}

	protected function getTemplate() {
		return 'CreateControl/ProjectCreateControl.php';
	}

}

