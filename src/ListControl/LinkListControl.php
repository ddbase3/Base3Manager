<?php

namespace Base3Manager\ListControl;

class LinkListControl extends AbstractListControl {

        // Implementation of IBase

        public function getName() {
                return "linklistcontrol";
        }

	// Implementation of AbstractListControl

	protected function getPath() {
		return DIR_PLUGIN . 'Base3Manager';
	}

	protected function getTemplate() {
		return 'ListControl/LinkListControl.php';
	}

}

