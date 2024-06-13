<?php

namespace Base3Manager\ToolbarControl;

class CopyEntryToolbarControl extends AbstractToolbarControl {

        // Implementation of IBase

        public function getName() {
                return "copyentrytoolbarcontrol";
        }

        // Implementation of AbstractToolbarControl

        protected function getPath() {
                return DIR_PLUGIN . 'Base3Manager';
        }

        protected function getTemplate() {
                return 'ToolbarControl/CopyEntryToolbarControl.php';
        }
}

