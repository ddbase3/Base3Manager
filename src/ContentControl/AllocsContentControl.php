<?php

namespace Base3Manager\ContentControl;

class AllocsContentControl extends AbstractContentControl {

        // Implementation of IBase

        public function getName() {
                return "allocscontentcontrol";
        }

	// Implementation of AbstractContentControl

        protected function getPath() {
                return DIR_PLUGIN . 'Base3Manager';
        }

        protected function getTemplate() {
                return 'ContentControl/AllocsContentControl.php';
        }

}

