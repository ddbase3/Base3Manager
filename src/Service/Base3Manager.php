<?php

namespace Base3Manager\Service;

class Base3Manager {

	private $servicelocator;
	private $classmap;

	private $plugins;

	public function __construct() {
		$this->servicelocator = \Base3\ServiceLocator::getInstance();
		$this->classmap = $this->servicelocator->get('classmap');

		$this->plugins = $this->classmap->getPlugins();
	}

	public function getModules() {

		$modules = array();

		foreach ($this->plugins as $plugin) {
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

		foreach ($this->plugins as $plugin) {
			$file = DIR_PLUGIN . $plugin . "/local/Module/" . $module . ".json";
			if (!file_exists($file)) continue;
			$content = file_get_contents($file);
			return json_decode($content, true);
		}

		return null;	
	}

	public function getFunctionalities() {

		$functionalities = array();

		foreach ($this->plugins as $plugin) {
			$file = DIR_PLUGIN . $plugin . '/local/functionalities.json';
			if (!file_exists($file)) continue;
			$content = file_get_contents($file);
			$functionalities = array_merge($functionalities, json_decode($content, true));
		}

		usort($functionalities, function($a, $b) {
			if ($a['order'] == $b['order']) return 0;
			return ($a['order'] < $b['order']) ? -1 : 1;
		});

		return $functionalities;
	}

	public function getScopes() {

		$scopes = array();

		foreach ($this->plugins as $plugin) {
			$file = DIR_PLUGIN . $plugin . '/local/scopes.json';
			if (!file_exists($file)) continue;
			$content = file_get_contents($file);
			$scopes = array_merge($scopes, json_decode($content, true));
		}

		usort($scopes, function($a, $b) {
			if ($a['order'] == $b['order']) return 0;
			return ($a['order'] < $b['order']) ? -1 : 1;
		});

		return $scopes;
	}

	public function getTypes() {

		$typedefinitions = array();

		foreach ($this->plugins as $plugin) {
			$file = DIR_PLUGIN . $plugin . '/local/types.json';
			if (!file_exists($file)) continue;
			$content = file_get_contents($file);
			$types = json_decode($content, true);
			foreach ($types as $type) {
				if (isset($typedefinitions[$type['type']]) && $type['priority'] <= $typedefinitions[$type['type']]['priority']) continue;
				$typedefinitions[$type['type']] = $type;
			}
		}

		return $typedefinitions;
	}
}

