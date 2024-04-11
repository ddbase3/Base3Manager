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

	public function getModules() {

		$modules = array();

		$plugins = $this->classmap->getPlugins();
		foreach ($plugins as $plugin) {
			$path = DIR_PLUGIN . $plugin . "/local/Module/";
			if (!is_dir($path)) continue;
			$files = scandir($path);
			foreach ($files as $file) {
				if (substr($file, -5) != '.json') continue;
				$content = file_get_contents($path . $file);
				$modules[] = json_decode($content, true);
			}
		}
		return $modules;
	}

	public function getModule($module) {

		$plugins = $this->classmap->getPlugins();
		foreach ($plugins as $plugin) {
			$file = DIR_PLUGIN . $plugin . "/local/Module/" . $module . ".json";
			if (!file_exists($file)) continue;
			$content = file_get_contents($file);
			return json_decode($content, true);
		}

		return null;	
	}

}
