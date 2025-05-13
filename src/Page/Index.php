<?php declare(strict_types=1);

namespace Base3Manager\Page;

use Base3\Api\IAssetResolver;
use Base3\Api\IMvcView;
use Base3\Api\IOutput;
use Base3\Configuration\Api\IConfiguration;
use Base3\Language\Api\ILanguage;
use Base3Manager\Service\Base3Manager;

class Index implements IOutput {

	public function __construct(
		private readonly IConfiguration $configuration,
		private readonly ILanguage $language,
		private readonly Base3Manager $base3manager,
		private readonly IMvcView $view,
		private readonly IAssetResolver $assetResolver
	) {}

	// Implementation of IBase

	public function getName() {
		return "index";
	}

	// Implementation of IOutput

	public function getOutput($out = "html") {

		$cnf = $this->configuration->get('manager');

		$this->view->setPath(DIR_PLUGIN . 'Base3Manager');
		$this->view->setTemplate('Page/Index.php');

		$this->view->assign('endpoint', $cnf['endpoint']);
		$this->view->assign('layout', $cnf['layout']);
		$this->view->assign('language', $this->language->getLanguage());
		$this->view->assign('assets', $this->base3manager->getAssets());
		$this->view->assign('systemnavi', $this->base3manager->getSystemNavi());

		$this->view->assign('resolve', fn($src) => $this->assetResolver->resolve($src));

		return $this->view->loadTemplate();
	}

	public function getHelp() {
		return 'Help of Index' . "\n";
	}

}
