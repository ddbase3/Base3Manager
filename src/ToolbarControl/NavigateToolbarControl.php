<?php

namespace Base3Manager\ToolbarControl;

class NavigateToolbarControl extends AbstractToolbarControl {

        // Implementation of IBase

        public function getName() {
                return "navigatetoolbarcontrol";
        }

        // Implementation of AbstractToolbarControl

        protected function getPath() {
                return DIR_PLUGIN . 'Base3Manager';
        }

        protected function getTemplate() {
                return 'ToolbarControl/NavigateToolbarControl.php';
        }
}

