<?php

namespace Base3Manager\ToolbarControl;

class EditEntryToolbarControl extends AbstractToolbarControl {

        // Implementation of IBase

        public function getName() {
                return "editentrytoolbarcontrol";
        }

        // Implementation of AbstractToolbarControl

        protected function getPath() {
                return DIR_PLUGIN . 'Base3Manager';
        }

        protected function getTemplate() {
                return 'ToolbarControl/EditEntryToolbarControl.php';
        }
}

