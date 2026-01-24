<?php declare(strict_types=1);

namespace Base3Manager;

use Base3\Api\IAssetResolver;
use Base3\Api\IClassMap;
use Base3\Api\IContainer;
use Base3\Api\IMvcView;
use Base3\Configuration\Api\IConfiguration;
use Base3\Core\BaseAssetResolver;
use Base3\Core\Check;
use Base3\Core\MvcView;
use Base3\Language\Api\ILanguage;
use Base3\Language\MultiLang\MultiLang;
use Base3\ServiceSelector\Api\IServiceSelector;
use Base3\ServiceSelector\LangBased\LangBasedServiceSelector;
use Base3\Session\Api\ISession;
use Base3\Session\NoSession\NoSession;
use Base3\State\Api\IStateStore;
use Base3\State\No\NoStateStore;
use Base3Manager\Plugin\AbstractPlugin;
use Base3Manager\Service\Base3Manager;

class Base3ManagerPlugin extends AbstractPlugin {

	// Implementation of IPlugin

	public function init() {

		$this->container

			->set(self::getName(), $this, IContainer::SHARED)

                        ->set(IServiceSelector::class, fn($c) => new LangBasedServiceSelector($c), IContainer::SHARED)
			->set('serviceselector', IServiceSelector::class, IContainer::ALIAS)

			// overwrite with plugins
			->set(ISession::class, fn() => new NoSession(), IContainer::SHARED | IContainer::NOOVERWRITE)

			->set(IStateStore::class, fn() => new NoStateStore(), IContainer::SHARED | IContainer::NOOVERWRITE)

			->set(ILanguage::class, fn($c) => new MultiLang($c->get(IConfiguration::class), $c->get(ISession::class)), IContainer::SHARED)
			->set('language', ILanguage::class, IContainer::ALIAS)

			->set(IMvcView::class, fn($c) => new MvcView($c->get(ILanguage::class)))
			->set('view', IMvcView::class, IContainer::ALIAS)

			->set(Base3Manager::class, fn($c) => new Base3Manager($c->get(IClassMap::class)), IContainer::SHARED)
			->set('base3manager', Base3Manager::class, IContainer::ALIAS)

			->set(IAssetResolver::class, fn() => new BaseAssetResolver(), IContainer::SHARED | IContainer::NOOVERWRITE)

			// for check only
			->set('delegateworker', fn() => new \Base3\Worker\DelegateWorker())

			->set('base3managerchecks', [ fn() => new Check($this->container) ]);
	}
}
