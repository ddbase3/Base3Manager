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
use Base3Manager\Plugin\AbstractPlugin;
use Base3Manager\Service\Base3Manager;

class Base3ManagerPlugin extends AbstractPlugin {

	// Implementation of IPlugin

	public function init() {

		$this->container

			->set(self::getName(), $this, IContainer::SHARED)

                        ->set('serviceselector', LangBasedServiceSelector::getInstance(), IContainer::SHARED)
			->set(IServiceSelector::class, 'serviceselector', IContainer::ALIAS)

			// overwrite with plugins
			->set(ISession::class, new NoSession, IContainer::SHARED | IContainer::NOOVERWRITE)

			->set('language',
				fn() => new MultiLang(
					$this->container->get(IConfiguration::class),
					$this->container->get(ISession::class)
				), IContainer::SHARED)
			->set(ILanguage::class, 'language', IContainer::ALIAS)

			->set('view', fn() => new MvcView)
			->set(IMvcView::class, 'view', IContainer::ALIAS)

			->set('base3manager', new Base3Manager($this->container->get(IClassMap::class)), IContainer::SHARED)
			->set(Base3Manager::class, 'base3manager', IContainer::ALIAS)

			->set(IAssetResolver::class, fn() => new BaseAssetResolver, IContainer::SHARED | IContainer::NOOVERWRITE)

			// for check only
			->set('delegateworker', fn() => new \Base3\Worker\DelegateWorker)

			->set('base3managerchecks', [ fn() => new Check($this->container) ]);
	}
}
