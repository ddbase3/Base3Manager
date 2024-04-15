<?php

namespace Base3Manager\ListControl;

class ProjectListControl extends AbstractListControl {

        // Implementation of IBase

        public function getName() {
                return "projectlistcontrol";
        }

	// Implementation of AbstractListControl

	protected function getPath() {
		return DIR_PLUGIN . 'Base3Manager';
	}

	protected function getTemplate() {
		return 'ListControl/ProjectListControl.php';
	}

}

