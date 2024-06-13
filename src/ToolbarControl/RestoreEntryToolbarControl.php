<?php

namespace Base3Manager\ToolbarControl;

class RestoreEntryToolbarControl extends AbstractToolbarControl {

        // Implementation of IBase

        public function getName() {
                return "restoreentrytoolbarcontrol";
        }

        // Implementation of AbstractToolbarControl

        protected function getPath() {
                return DIR_PLUGIN . 'Base3Manager';
        }

        protected function getTemplate() {
                return 'ToolbarControl/RestoreEntryToolbarControl.php';
        }
}

