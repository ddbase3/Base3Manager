<?php

namespace Base3Manager\Page;

use Api\IOutput;

class Subnavi implements IOutput {

	private $servicelocator;
	private $configuration;
	private $accesscontrol;
	private $base3manager;

	public function __construct() {
		$this->servicelocator = \Base3\ServiceLocator::getInstance();
		$this->configuration = $this->servicelocator->get('configuration');
		$this->accesscontrol = $this->servicelocator->get('accesscontrol');
		$this->base3manager = $this->servicelocator->get('base3manager');
	}

	// Implementation of IBase

	public function getName() {
		return "subnavi";
	}

	// Implementation of IOutput

	public function getOutput($out = "html") {

                if (!isset($_REQUEST["alias"])) die();
                $alias = str_replace("/", "", $_REQUEST["alias"]);

                $module = $this->base3manager->getModule($alias);
                if (!$module) return '';

		if (!isset($module['list'])) $module['list'] = 'standardlistcontrol';

                $view = $this->servicelocator->get('view');
                $view->setPath(DIR_PLUGIN . 'Base3Manager');
                $view->setTemplate('Page/Subnavi.php');
                $view->assign("alias", $alias);
		$view->assign("module", $module);

		$manager = $this->configuration->get('manager');
                $view->assign("manager", $manager);

		$subnavi = isset($module['subnavi']) ? $module['subnavi'] : array();
		uasort($subnavi, function($a, $b) {
                        if ($a['order'] == $b['order']) return 0;
                        return ($a['order'] < $b['order']) ? -1 : 1;
                });

                $authenticated = !!$this->accesscontrol->getUserId();
                foreach ($subnavi as $key => $sub) {
                        $enabled = 0;
                        if (isset($sub['enabled'])) {
                                if (is_array($sub['enabled'])) {
                                        if (isset($sub['enabled']['authenticated']) && $sub['enabled']['authenticated'] && $authenticated) $enabled = 1;
                                } else {
                                        $enabled = $sub['enabled'];
                                }
                        }
                        if (!$enabled) unset($subnavi[$key]);
                }

		$view->assign("subnavi", $subnavi);

		return $view->loadTemplate();
	}

	public function getHelp() {
		return 'Help of Subnavi' . "\n";
	}

}

