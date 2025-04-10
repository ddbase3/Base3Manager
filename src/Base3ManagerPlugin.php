<?php declare(strict_types=1);

namespace Base3Manager;

use Base3Manager\Plugin\AbstractPlugin;
use Base3\Api\IContainer;
use Base3\Api\IConfiguration;
use Base3\Session\Api\ISession;
use Base3\Api\IClassMap;
use Base3\Language\MultiLang\MultiLang;

class Base3ManagerPlugin extends AbstractPlugin {

	// Implementation of IPlugin

	public function init() {

		$this->container

			->set(
				$this->getName(),
				$this,
				IContainer::SHARED)

                        ->set(
                                'serviceselector',
                                \Base3\ServiceSelector\LangBased\LangBasedServiceSelector::getInstance(),
                                IContainer::SHARED)

			->set(
				'language',
				function() {
/*
					$configuration = $this->container->get(IConfiguration::class);
					$session = $this->container->get(ISession::class);
					return new MultiLang($configuration, $session);
*/
					return $this->container->get(IClassMap::class)->instantiate(MultiLang::class);
				},
				IContainer::SHARED)

			->set(
				'view',
				function() {
					return new \Base3\Core\MvcView;
				})

			->set(
				\Base3\Api\IMvcView::class,
				'view',
				IContainer::ALIAS)

			->set(
				'base3manager',
				new \Base3Manager\Service\Base3Manager,
				IContainer::SHARED)

			->set(
				\Base3Manager\Service\Base3Manager::class,
				'base3manager',
				IContainer::ALIAS)

			->set(
				'base3managerchecks',
				array(
                                	function() { return new \Base3\Core\Check($this->container); }
                         	));
	}
}
