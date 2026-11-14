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
use Base3\CoreFoundation\Api\IMvcView;
use Base3\CoreFoundation\Api\IOutput;
use Base3\CoreFoundation\Accesscontrol\Api\IAccesscontrol;
use Base3\CoreFoundation\Configuration\Api\IConfiguration;
use Base3\Base3Manager\Service\Base3Manager;

class Toolbar implements IOutput {

	private $configuration;
	private $classmap;
	private $accesscontrol;
	private $base3manager;
	private $view;

	public function __construct(
		IConfiguration $configuration,
		IClassMap $classmap,
		IAccesscontrol $accesscontrol,
		Base3Manager $base3manager,
		IMvcView $view
	) {
		$this->configuration = $configuration;
		$this->classmap = $classmap;
		$this->accesscontrol = $accesscontrol;
		$this->base3manager = $base3manager;
		$this->view = $view;
	}

	// Implementation of IBase

	public static function getName(): string {
		return "toolbar";
	}

	// Implementation of IOutput

	public function getOutput(string $out = 'html', bool $final = false): string {

                if (!isset($_REQUEST["alias"])) die();
                $alias = str_replace("/", "", $_REQUEST["alias"]);

                $module = $this->base3manager->getModule($alias);
                if (!$module) return '';

		if (!isset($module['list'])) $module['list'] = 'standardlistcontrol';

                $this->view->setPath(DIR_PLUGIN . 'Base3Manager');
                $this->view->setTemplate('Page/Toolbar.php');
                $this->view->assign("alias", $alias);
		$this->view->assign("module", $module);

		$manager = $this->configuration->get('manager');
                $this->view->assign("manager", $manager);

		// toolbar

		$toolbar = array();

		$toolbarcontrols = $this->base3manager->getToolbarControls();
		if (isset($module['toolbar'])) foreach ($module['toolbar'] as $toolgroup) {
			$group = array();
			foreach ($toolgroup as $tool) {
				foreach ($toolbarcontrols as $control) {
					if ($control['tool'] != $tool) continue;

					$instance = $this->classmap->getInstanceByInterfaceName(IOutput::class, $control['control']);
					if ($instance == null) continue;
					$instance->setAlias($alias);
					$instance->setTool($control);
					$group[] = $instance->getOutput();
				}
			}
			$toolbar[] = $group;
		}

		$this->view->assign("toolbar", $toolbar);

		return $this->view->loadTemplate();
	}

	public function getHelp(): string {
		return 'Help of Toolbar' . "\n";
	}
}
