<?php

namespace Base3Manager\ToolbarControl;

class SuggestEntryToolbarControl extends AbstractToolbarControl {

        // Implementation of IBase

        public function getName() {
                return "suggestentrytoolbarcontrol";
        }

        // Implementation of AbstractToolbarControl

        protected function getPath() {
                return DIR_PLUGIN . 'Base3Manager';
        }

        protected function getTemplate() {
                return 'ToolbarControl/SuggestEntryToolbarControl.php';
        }
}

