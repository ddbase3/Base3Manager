<?php declare(strict_types=1);

namespace Base3Manager\Page;

use Base3\Api\IClassMap;
use Base3\Api\IMvcView;
use Base3\Api\IOutput;
use Base3\Accesscontrol\Api\IAccesscontrol;
use Base3\Configuration\Api\IConfiguration;
use Base3Manager\Service\Base3Manager;

class Subnavi implements IOutput {

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
		return "subnavi";
	}

	// Implementation of IOutput

	public function getOutput($out = "html") {

                if (!isset($_REQUEST["alias"])) die();
                $alias = str_replace("/", "", $_REQUEST["alias"]);

                $module = $this->base3manager->getModule($alias);
                if (!$module) return '';

		if (!isset($module['list'])) $module['list'] = 'standardlistcontrol';

                $this->view->setPath(DIR_PLUGIN . 'Base3Manager');
                $this->view->setTemplate('Page/Subnavi.php');
                $this->view->assign("alias", $alias);
		$this->view->assign("module", $module);
                $this->view->assign('plugin', $module['plugin']);

		$manager = $this->configuration->get('manager');
                $this->view->assign("manager", $manager);

		// subnavi

		$subnavi = isset($module['subnavi']) ? $module['subnavi'] : array();
		uasort($subnavi, function($a, $b) {
                        if ($a['order'] == $b['order']) return 0;
                        return ($a['order'] < $b['order']) ? -1 : 1;
                });

                $authenticated = isset($this->accesscontrol) && !!$this->accesscontrol->getUserId();
                foreach ($subnavi as $key => $sub) {
                        $enabled = 0;
                        if (isset($sub['enabled'])) {
                                if (is_array($sub['enabled'])) {
                                        if (isset($sub['enabled']['authenticated']) && $sub['enabled']['authenticated'] && $authenticated) $enabled = 1;
                                } else {
                                        $enabled = $sub['enabled'];
                                }
                        }
                        if (!$enabled) unset($subnavi[$key]);
                }

		$this->view->assign("subnavi", $subnavi);

		return $this->view->loadTemplate();
	}

	public function getHelp() {
		return 'Help of Subnavi' . "\n";
	}
}

