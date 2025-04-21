<?php declare(strict_types=1);

namespace Base3Manager\Service;

use Base3\Api\IClassMap;

class Base3Manager {

	private $classmap;

	private $plugins;

	public function __construct(IClassMap $classmap) {
		$this->classmap = $classmap;

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
				$data = json_decode($content, true);
				$data['plugin'] = $plugin;
				$modules[] = $data;
			}
		}
		return $modules;
	}

	public function getModule($module) {

		foreach ($this->plugins as $plugin) {
			$file = DIR_PLUGIN . $plugin . "/local/Module/" . $module . ".json";
			if (!file_exists($file)) continue;
			$content = file_get_contents($file);
			$data = json_decode($content, true);
			$data['plugin'] = $plugin;
			return $data;
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

	public function getScopes(): array {

		$scopes = [];

		foreach ($this->plugins as $plugin) {
			$file = DIR_PLUGIN . $plugin . '/local/scopes.json';
			if (!file_exists($file)) continue;

			$content = file_get_contents($file);
			$decoded = json_decode($content, true);

			if (!is_array($decoded)) continue;

			foreach ($decoded as $s) {
				if (empty($s['active']) || !isset($s['scope'])) continue;
				if (!isset($scopes[$s['scope']])) $scopes[$s['scope']] = $s;
			}
		}

		$scopes = array_values($scopes);
		usort($scopes, fn($a, $b) => ($a['order'] ?? 0) <=> ($b['order'] ?? 0));

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

	public function getAssets() {

		$assets = array();

		foreach ($this->plugins as $plugin) {
			$file = DIR_PLUGIN . $plugin . '/local/assets.json';
			if (!file_exists($file)) continue;
			$content = file_get_contents($file);
			$data = json_decode($content, true);
			$assets = array_merge($assets, $data);
		}

		return $assets;
	}

	public function getSystemNavi() {

		$systemnavi = array();

		foreach ($this->plugins as $plugin) {
			$file = DIR_PLUGIN . $plugin . '/local/systemnavi.json';
			if (!file_exists($file)) continue;
			$content = file_get_contents($file);
			$data = json_decode($content, true);
			$navi = array(
				'plugin' => $plugin,
				'data' => $data
			);
			$systemnavi[] = $navi;
		}

		return $systemnavi;
	}

	public function getToolbarControls() {

		$controls = array();

		foreach ($this->plugins as $plugin) {
			$file = DIR_PLUGIN . $plugin . '/local/toolbar.json';
			if (!file_exists($file)) continue;
			$content = file_get_contents($file);
			$data = json_decode($content, true);
			$controls = array_merge($controls, $data);
		}

		return $controls;
	}
}

