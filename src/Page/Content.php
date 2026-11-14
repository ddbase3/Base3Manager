<?php declare(strict_types=1);

/***********************************************************************
 * This file is part of Base3Manager for BASE3 Framework.
 *
 * Base3Manager provides manager pages and controls for BASE3.
 * It composes administration navigation, toolbars and views.
 * Extensions contribute controls through the existing plugin contracts.
 *
 * Developed by Daniel Dahme
 * Licensed under GPL-3.0
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 *
 * https://base3.de
 ***********************************************************************/


namespace Base3\Base3Manager\Page;

use Base3\CoreFoundation\Api\IClassMap;
use Base3\CoreFoundation\Api\IDisplay;
use Base3\CoreFoundation\Api\IOutput;
use Base3\Base3Manager\Service\Base3Manager;

class Content implements IOutput {

        private $classmap;
        private $base3manager;

        public function __construct(
		IClassmap $classmap,
		Base3Manager $base3manager
	) {
                $this->classmap = $classmap;
                $this->base3manager = $base3manager;
        }

	// Implementation of IBase

	public static function getName(): string {
		return "content";
	}

	// Implementation of IOutput

	public function getOutput(string $out = 'html', bool $final = false): string {

		if (!isset($_REQUEST["alias"])) die();
		$alias = str_replace("/", "", $_REQUEST["alias"]);

		$oldstyle = 0;
		$content_include_file = "";  // TODO remove old style
		$control = null;
		$displayData = null;
		$hasDisplayData = false;
		$module = $this->base3manager->getModule($alias);
		if (isset($_REQUEST["tabalias"]) && isset($module['tabs'])) {
			foreach ($module['tabs'] as $tab) {
				if ($tab['tab'] != $_REQUEST["tabalias"]) continue;
				if (isset($tab['content'])) {
					$control = $tab['content'];
					$displayData = null;
					$hasDisplayData = false;
					if (array_key_exists('data', $tab)) {
						$displayData = $tab['data'];
						$hasDisplayData = true;
					}
				} else {
					// TODO remove old style
					$oldstyle = 1;
					$tabalias = str_replace("/", "", $_REQUEST["tabalias"]);
					$content_include_file = "modules/".$alias."/tabs/".$tabalias."/content.php";
				}
				break;
			}
		}
		if (isset($_REQUEST["subnavialias"]) && isset($module['subnavi'])) {
			foreach ($module['subnavi'] as $subnavi) {
				if ($subnavi['subnavi'] != $_REQUEST["subnavialias"]) continue;
				if (isset($subnavi['content'])) {
					$control = $subnavi['content'];
					$displayData = null;
					$hasDisplayData = false;
					if (array_key_exists('data', $subnavi)) {
						$displayData = $subnavi['data'];
						$hasDisplayData = true;
					}
				} else {
					// TODO remove old style
					$oldstyle = 1;
					$subnavialias = str_replace("/", "", $_REQUEST["subnavialias"]);
					$content_include_file = "modules/".$alias."/subnavi/".$subnavialias."/content.php";
				}
				break;
			}
		}

/*
		$content_include_file = "";
		if (isset($_REQUEST["tabalias"])) {
			$tabalias = str_replace("/", "", $_REQUEST["tabalias"]);
			$content_include_file = "modules/".$alias."/tabs/".$tabalias."/content.php";
		}
		if (isset($_REQUEST["subnavialias"])) {
			$subnavialias = str_replace("/", "", $_REQUEST["subnavialias"]);
			$content_include_file = "modules/".$alias."/subnavi/".$subnavialias."/content.php";
		}
*/

		if ($oldstyle) {
			if (!file_exists($content_include_file)) die();

			include("modules/".$alias."/module.php");
			$class = strtoupper(substr($alias, 0, 1)).substr($alias, 1).'Module';
			$module = new $class();

			ob_start();
			include($content_include_file);
			$out = ob_get_contents();
			ob_end_clean();
		} else {
			if ($control == null) die();
			$interface = $hasDisplayData ? IDisplay::class : IOutput::class;
			$instance = $this->classmap->getInstanceByInterfaceName($interface, $control);
			if ($instance == null) die();
			if ($hasDisplayData) {
				$instance->setData($displayData);
			}
			return $instance->getOutput();
		}

		return $out;
	}

	public function getHelp(): string {
		return 'Help of Content' . "\n";
	}
}
