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


namespace Base3\Base3Manager;

use Base3\CoreFoundation\Api\IAssetResolver;
use Base3\CoreFoundation\Api\IClassMap;
use Base3\CoreFoundation\Api\IContainer;
use Base3\CoreFoundation\Api\IMvcView;
use Base3\CoreFoundation\Configuration\Api\IConfiguration;
use Base3\Core\BaseAssetResolver;
use Base3\Core\Check;
use Base3\Core\MvcView;
use Base3\CoreFoundation\Event\Api\IEventManager;
use Base3\Core\Event\EventManager;
use Base3\CoreFoundation\Language\Api\ILanguage;
use Base3\Core\Language\MultiLang\MultiLang;
use Base3\CoreFoundation\LinkTarget\Api\ILinkTargetService;
use Base3\Core\LinkTarget\Standard\StandardLinkTargetService;
use Base3\CoreFoundation\ServiceSelector\Api\IServiceSelector;
use Base3\Core\ServiceSelector\LangBased\LangBasedServiceSelector;
use Base3\CoreFoundation\Session\Api\ISession;
use Base3\Core\Session\NoSession\NoSession;
use Base3\CoreFoundation\State\Api\IStateStore;
use Base3\Core\State\No\NoStateStore;
use Base3\Base3Manager\Plugin\AbstractPlugin;
use Base3\Base3Manager\Service\Base3Manager;

class Base3ManagerPlugin extends AbstractPlugin {

	// Implementation of IPlugin

	public function init() {

		$this->container

			->set(self::getName(), $this, IContainer::SHARED)

                        ->set(IServiceSelector::class, fn($c) => new LangBasedServiceSelector($c), IContainer::SHARED)
			->set('serviceselector', IServiceSelector::class, IContainer::ALIAS)

			->set(ILinkTargetService::class, fn() => new StandardLinkTargetService(), IContainer::SHARED | IContainer::NOOVERWRITE)

			// overwrite with plugins
			->set(ISession::class, fn() => new NoSession(), IContainer::SHARED | IContainer::NOOVERWRITE)

			->set(IEventManager::class, fn() => new EventManager(), IContainer::SHARED)

			->set(IStateStore::class, fn() => new NoStateStore(), IContainer::SHARED | IContainer::NOOVERWRITE)

			->set(ILanguage::class, fn($c) => new MultiLang($c->get(IConfiguration::class), $c->get(ISession::class)), IContainer::SHARED)
			->set('language', ILanguage::class, IContainer::ALIAS)

			->set(IMvcView::class, fn($c) => new MvcView($c->get(ILanguage::class)))
			->set('view', IMvcView::class, IContainer::ALIAS)

			->set(Base3Manager::class, fn($c) => new Base3Manager($c->get(IClassMap::class)), IContainer::SHARED)
			->set('base3manager', Base3Manager::class, IContainer::ALIAS)

			->set(IAssetResolver::class, fn() => new BaseAssetResolver(), IContainer::SHARED | IContainer::NOOVERWRITE)

			// for check only
			->set('delegateworker', fn() => new \Base3\Core\Worker\DelegateWorker())

			->set('base3managerchecks', [ fn() => new Check($this->container) ]);
	}
}
