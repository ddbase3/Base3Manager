<?php

namespace Base3Manager\ListControl;

class ContactListControl extends AbstractListControl {

        // Implementation of IBase

        public function getName() {
                return "contactlistcontrol";
        }

	// Implementation of AbstractListControl

	protected function getPath() {
		return DIR_PLUGIN . 'Base3Manager';
	}

	protected function getTemplate() {
		return 'ListControl/ContactListControl.php';
	}

}

