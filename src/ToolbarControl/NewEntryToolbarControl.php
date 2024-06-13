<?php

namespace Base3Manager\ToolbarControl;

class NewEntryToolbarControl extends AbstractToolbarControl {

        // Implementation of IBase

        public function getName() {
                return "newentrytoolbarcontrol";
        }

        // Implementation of AbstractToolbarControl

        protected function fillView() {
                $base3manager = $this->servicelocator->get('base3manager');
                $module = $base3manager->getModule($this->alias);
                $this->view->assign('module', $module);
        }

        protected function getPath() {
                return DIR_PLUGIN . 'Base3Manager';
        }

        protected function getTemplate() {
                return 'ToolbarControl/NewEntryToolbarControl.php';
        }
}

