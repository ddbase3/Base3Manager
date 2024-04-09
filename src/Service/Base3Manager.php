<?php

namespace Base3Manager\Service;

class Base3Manager {

	private $servicelocator;
	private $classmap;

	private $config;

	public function __construct() {
		$this->servicelocator = \Base3\ServiceLocator::getInstance();
		$this->classmap = $this->servicelocator->get('classmap');

		$this->config = file_get_contents('inc/config.json');
	}

	public function getModule($module) {

		$plugins = $this->classmap->getPlugins();
		foreach($plugins as $plugin) {
			$file = rtrim(DIR_PLUGIN, DIRECTORY_SEPARATOR) . "/" . $plugin . "/local/Module/" . $module . ".json";
			if (!file_exists($file)) continue;
			$content = file_get_contents($file);
			return json_decode($content, true);
		}

		return null;	
	}

}
