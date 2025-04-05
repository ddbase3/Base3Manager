<?php declare(strict_types=1);

namespace Base3Manager;

use Base3\Core\ServiceLocator;
use Base3Manager\Plugin\AbstractPlugin;

class Base3ManagerPlugin extends AbstractPlugin {

	// Implementation of IPlugin

	public function init() {
		$this->servicelocator

			->set(
				$this->getName(),
				$this,
				ServiceLocator::SHARED)

                        ->set(
                                'serviceselector',
                                \Base3\ServiceSelector\LangBased\LangBasedServiceSelector::getInstance(),
                                ServiceLocator::SHARED)

			->set(
				'language',
				function() {
					return new \Base3\Language\MultiLang\MultiLang;
				},
				ServiceLocator::SHARED)

			->set(
				'view',
				function() {
					return new \Base3\Core\MvcView;
				})

			->set(
				'base3manager',
				new \Base3Manager\Service\Base3Manager,
				ServiceLocator::SHARED)

			->set(
				'base3managerchecks',
				array(
                                	function() { return new \Base3\Core\Check; }
                         	));
	}
}
