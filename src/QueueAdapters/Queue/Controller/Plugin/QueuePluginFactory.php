<?php
/**
 * Queue Adapter Module for Zend Framework
 *
 * @link      https://github.com/joachimdo/zf2-queueing-factory-module
 * @copyright Copyright (c) 2014 Joachim Dornbusch
 * @license   GNU-GPL V3+
 */

namespace QueueAdapters\Queue\Controller\Plugin;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZendQueue\Queue;
use QueueAdapters\Queue\Service\QueueAdaptersAbstractServiceFactory;
use Zend\ServiceManager\FactoryInterface;
use Zend\Stdlib\ArrayUtils;

/**
 * Queue adapters factory for different implementations.
 */
class QueuePluginFactory implements FactoryInterface
{

	/**
	 * @var array
	 */
	protected $config;

	/**
	 * Create service
	 *
	 * @param ServiceLocatorInterface $serviceLocator
	 * @return mixed
	 */
	public function createService(ServiceLocatorInterface $serviceLocator) {
		$queue=null;
		//THe provided service locator is only the Plugin manager :
		//let's retrieve the main service locator
		$serviceManager=$serviceLocator->getServiceLocator();
		$config = $this->getConfig($serviceManager);
		$configKey=$this->chooseAmongConfigs($config);
		$queue=$serviceManager->get($configKey);
		if($queue instanceof Queue ) {
			$plugin = new QueuePlugin($queue);
			return $plugin;
		}
		throw new \Exception("$configKey is not a queue implementation offered as a service by this module");

	}
	/**
	 * Retrieve configuration for this queue implementation
	 *
	 * @param  ServiceLocatorInterface $services
	 * @return array
	 */
	protected function getConfig(ServiceLocatorInterface $services)
	{
		if ($this->config !== null) {
			return $this->config;
		}
		if (!$services->has('Config')) {
			$this->config = array();
			return $this->config;
		}
		$config = $services->get('Config');
		if (!isset($config[QueueAdaptersAbstractServiceFactory::$configKey])) {
			$this->config = array();
			return $this->config;
		}
		$this->config = $config[QueueAdaptersAbstractServiceFactory::$configKey];
		return $this->config;
	}
	/**
	 * Choose a queue implementation : the "default "one" if specified, else the first one
	 *
	 * @param  ServiceLocatorInterface $services
	 * @return array
	 */
	protected function chooseAmongConfigs($config) {
		if(array_key_exists("default", $config) && array_key_exists($config["default"], $config))
			return $config["default"];
		reset($config);
		$key= key($config);
		if(isset($key))
			return $key;
		throw new \Exception("There is no information under the key ".QueueAdaptersAbstractServiceFactory::$configKey." in you config. Please specify an implementation as default or fill the configuration for at least one implemention.");

	}

}
