<?php declare(strict_types=1);

namespace Base3Manager\Page;

use Base3\Api\IMvcView;
use Base3\Api\IOutput;
use Base3\Accesscontrol\Api\IAccesscontrol;
use Base3\Configuration\Api\IConfiguration;
use Base3Manager\Service\Base3Manager;

class ModuleNavi implements IOutput {

	private $configuration;
	private $accesscontrol;
	private $base3manager;
	private $view;

	public function __construct(
		IConfiguration $configuration,
		IAccesscontrol $accesscontrol,
		Base3Manager $base3manager,
		IMvcView $view
	) {
		$this->configuration = $configuration;
		$this->accesscontrol = $accesscontrol;
		$this->base3manager = $base3manager;
		$this->view = $view;
	}

	// Implementation of IBase

	public function getName() {
		return "modulenavi";
	}

	// Implementation of IOutput

	public function getOutput($out = "html") {

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

		$this->view->assign("modules", $modules);

		return $this->view->loadTemplate();
	}

	public function getHelp() {
		return 'Help of ModuleNavi' . "\n";
	}

}
