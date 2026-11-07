<?php declare(strict_types=1);

namespace Base3Manager\Translation;

use Base3\Api\IModuleRegistry;
use Base3\Language\Api\ILanguage;
use Base3\Translation\Api\ITranslation;

/** Resolves plugin-owned language bricks through the existing module registry. */
final class ModuleTranslation implements ITranslation {

	/** @var array<string,array<string,array<string,string>>> */
	private array $catalogs = [];

	public function __construct(
		private readonly IModuleRegistry $modules,
		private readonly ILanguage $language
	) {}

	public function translate(string $set, string $section, string $key, string $fallback = '', array $replacements = []): string {
		$language = $this->language->getLanguage();
		$catalogKey = $set . '/' . $language;
		if (!isset($this->catalogs[$catalogKey])) {
			$sections = [];
			foreach ($this->modules->getModuleNames() as $moduleName) {
				$modulePath = $this->modules->getModulePath($moduleName);
				if ($modulePath === null) continue;
				$filename = $modulePath . '/lang/' . $set . '/' . $language . '.ini';
				if (!is_file($filename)) continue;
				$bricks = parse_ini_file($filename, true);
				if (!is_array($bricks)) continue;
				foreach ($bricks as $sectionName => $entries) {
					if (!is_array($entries)) continue;
					if (!isset($sections[$sectionName])) $sections[$sectionName] = $entries;
				}
			}
			$this->catalogs[$catalogKey] = $sections;
		}

		$text = $this->catalogs[$catalogKey][$section][$key] ?? $fallback;
		if (!is_string($text)) $text = $fallback;
		if ($text === '') $text = $key;
		foreach ($replacements as $name => $value) {
			$text = str_replace('{' . $name . '}', (string)$value, $text);
		}
		return $text;
	}
}
