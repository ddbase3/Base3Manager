<?php declare(strict_types=1);

namespace Base3Manager\Page;

use Base3\Api\IMvcView;
use Base3\Api\IOutput;
use Base3\Accesscontrol\Api\IAccesscontrol;
use Base3Manager\Service\Base3Manager;

class Tabs implements IOutput {

	private $accesscontrol;
	private $base3manager;
	private $view;

	public function __construct(
		IAccesscontrol $accesscontrol,
		Base3Manager $base3manager,
		IMvcView $view
	) {
		$this->accesscontrol = $accesscontrol;
		$this->base3manager = $base3manager;
		$this->view = $view;
	}

	// Implementation of IBase

	public static function getName(): string {
		return "tabs";
	}

	// Implementation of IOutput

	public function getOutput(string $out = 'html', bool $final = false): string {

		if (!isset($_REQUEST['alias'])) die();
		$alias = str_replace('/', '', $_REQUEST['alias']);

		$module = $this->base3manager->getModule($alias);
		if (!$module || !isset($module['tabs'])) return '';

		$this->view->setPath(DIR_PLUGIN . 'Base3Manager');
		$this->view->setTemplate('Page/Tabs.php');
		$this->view->assign('alias', $alias);
		$this->view->assign('plugin', $module['plugin']);

		$tabs = $module['tabs'];
		uasort($tabs, function($a, $b) {
			if ($a['order'] == $b['order']) return 0;
			return ($a['order'] < $b['order']) ? -1 : 1;
		});

		$authenticated = isset($this->accesscontrol) && !!$this->accesscontrol->getUserId();
		foreach ($tabs as $key => $tab) {
			$enabled = 0;
			if (isset($tab['enabled'])) {
				if (is_array($tab['enabled'])) {
					if (isset($tab['enabled']['authenticated']) && $tab['enabled']['authenticated'] && $authenticated) $enabled = 1;
				} else {
					$enabled = $tab['enabled'];
				}
			}
			if (!$enabled) unset($tabs[$key]);
		}

		$this->view->assign('tabs', $tabs);

		return $this->view->loadTemplate();
	}

	public function getHelp(): string {
		return 'Help of Tabs' . "\n";
	}
}
