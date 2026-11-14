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

use Base3\CoreFoundation\Api\IAssetResolver;
use Base3\CoreFoundation\Api\IMvcView;
use Base3\CoreFoundation\Api\IOutput;
use Base3\CoreFoundation\Accesscontrol\Api\IAccesscontrol;
use Base3\CoreFoundation\Configuration\Api\IConfiguration;
use Base3\Base3Manager\Service\Base3Manager;

class ModuleNavi implements IOutput {

	public function __construct(
		private readonly IConfiguration $configuration,
		private readonly IAccesscontrol $accesscontrol,
		private readonly Base3Manager $base3manager,
		private readonly IMvcView $view,
		private readonly IAssetResolver $assetresolver
	) {}

	// Implementation of IBase

	public static function getName(): string {
		return "modulenavi";
	}

	// Implementation of IOutput

	public function getOutput(string $out = 'html', bool $final = false): string {

		$cnf = $this->configuration->get('manager');
		define("SCOPE", isset($_REQUEST["scope"]) && strlen($_REQUEST["scope"]) ? $_REQUEST["scope"] : $cnf['stdscope']);

		$this->view->setPath(DIR_PLUGIN . 'Base3Manager');
		$this->view->setTemplate('Page/ModuleNavi.php');

		$modules = $this->base3manager->getModules();
		uasort($modules, function($a, $b) {
			$ao = isset($a['order']) ? $a['order'] : 0;
			$bo = isset($b['order']) ? $b['order'] : 0;
			if ($ao == $bo) return 0;
			return ($ao < $bo) ? -1 : 1;
		});

                $authenticated = isset($this->accesscontrol) && !!$this->accesscontrol->getUserId();
                foreach ($modules as $key => $module) {
                        $enabled = 0;
                        if (isset($module['enabled'])) {
                                if (is_array($module['enabled'])) {
					$enabled = 1;
                                        if (isset($module['enabled']['authenticated'])) $enabled &= (!!$module['enabled']['authenticated']) == (!!$authenticated);
					if (isset($module['enabled']['scope'])) $enabled &= in_array(SCOPE, $module['enabled']['scope']);
					if (isset($module['enabled']['noscope'])) $enabled &= !in_array(SCOPE, $module['enabled']['noscope']);
                                } else {
                                        $enabled = $module['enabled'];
                                }
                        }
                        if (!$enabled) unset($modules[$key]);
                }

		$this->view->assign('modules', $modules);

		$this->view->assign('resolve', fn($src) => $this->assetresolver->resolve($src));

		return $this->view->loadTemplate();
	}

	public function getHelp(): string {
		return 'Help of ModuleNavi' . "\n";
	}
}
