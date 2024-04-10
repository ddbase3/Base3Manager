<?php

namespace Base3Manager\HeaderControl;

class DateHeaderControl extends AbstractHeaderControl {

        // Implementation of IBase

        public function getName() {
                return "dateheadercontrol";
        }

	// Implementation of AbstractHeaderControl

        protected function getPath() {
                return DIR_PLUGIN . 'Base3Manager';
        }

        protected function getTemplate() {
                return 'HeaderControl/DateHeaderControl.php';
        }

}

