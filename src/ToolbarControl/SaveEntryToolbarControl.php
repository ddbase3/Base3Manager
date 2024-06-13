<?php

namespace Base3Manager\ToolbarControl;

class SaveEntryToolbarControl extends AbstractToolbarControl {

        // Implementation of IBase

        public function getName() {
                return "saveentrytoolbarcontrol";
        }

        // Implementation of AbstractToolbarControl

        protected function getPath() {
                return DIR_PLUGIN . 'Base3Manager';
        }

        protected function getTemplate() {
                return 'ToolbarControl/SaveEntryToolbarControl.php';
        }
}

