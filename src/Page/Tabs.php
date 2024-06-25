<?php

namespace Base3Manager\Page;

use Api\IOutput;

class Tabs implements IOutput {

	private $servicelocator;
	private $accesscontrol;
	private $base3manager;

	public function __construct() {
		$this->servicelocator = \Base3\ServiceLocator::getInstance();
		$this->accesscontrol = $this->servicelocator->get('accesscontrol');
		$this->base3manager = $this->servicelocator->get('base3manager');
	}

	// Implementation of IBase

	public function getName() {
		return "tabs";
	}

	// Implementation of IOutput

	public function getOutput($out = "html") {

		if (!isset($_REQUEST["alias"])) die();
		$alias = str_replace("/", "", $_REQUEST["alias"]);

		$module = $this->base3manager->getModule($alias);
		if (!$module || !isset($module["tabs"])) return '';

		$view = $this->servicelocator->get('view');
		$view->setPath(DIR_PLUGIN . 'Base3Manager');
		$view->setTemplate('Page/Tabs.php');
		$view->assign("alias", $alias);

		$tabs = $module['tabs'];
		uasort($tabs, function($a, $b) {
			if ($a['order'] == $b['order']) return 0;
			return ($a['order'] < $b['order']) ? -1 : 1;
		});

		$authenticated = isset($this->accesscontrol) && !!$this->accesscontrol->getUserId();
		foreach ($tabs as $key => $tab) {
			$enabled = 0;
			if (isset($tab['enabled'])) {
				if (is_array($tab['enabled'])) {
					if (isset($tab['enabled']['authenticated']) && $tab['enabled']['authenticated'] && $authenticated) $enabled = 1;
				} else {
					$enabled = $tab['enabled'];
				}
			}
			if (!$enabled) unset($tabs[$key]);
		}

		$view->assign("tabs", $tabs);

		return $view->loadTemplate();
	}

	public function getHelp() {
		return 'Help of Tabs' . "\n";
	}

}
