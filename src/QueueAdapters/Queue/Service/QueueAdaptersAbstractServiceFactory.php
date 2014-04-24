<?php
/**
 * Queue Adapter Module for Zend Framework
 *
 * @link      https://github.com/joachimdo/zf2-queueing-factory-module
 * @copyright Copyright (c) 2014 Joachim Dornbusch
 * @license   GNU-GPL V3+
 */

namespace QueueAdapters\Queue\Service;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZendQueue\Queue;

/**
 * Queue adapters factory for different implementations.
 */
class QueueAdaptersAbstractServiceFactory implements AbstractFactoryInterface
{
	/**
	 * @var array
	 */
	protected $config;

	/**
	 * Configuration key for queue implementations
	 *
	 * @var string
	 */
	protected static $configKey = 'queue-adapters';

	/**
	 * List of supported implementations
	 *
	 * @var array
	 */
	protected static $invokableClasses = array(
			'activemq' => 'ZendQueue\Adapter\Activemq'
	);


	/**
	 * @param  ServiceLocatorInterface $services
	 * @param  string                  $name
	 * @param  string                  $requestedName
	 * @return bool
	*/
	public function canCreateServiceWithName(ServiceLocatorInterface $services, $name, $requestedName)
	{
		return (array_key_exists($requestedName, self::$invokableClasses));
	}

	/**
	 * @param  ServiceLocatorInterface              $services
	 * @param  string                               $name
	 * @param  string                               $requestedName
	 * @return \Zend\Cache\Storage\StorageInterface
	 */
	public function createServiceWithName(ServiceLocatorInterface $services, $name, $requestedName)
	{
		$config = $this->getConfig($services);
		$queue=null;
		if($this->canCreateServiceWithName($services, $name, $requestedName)) {
			if(!array_key_exists($requestedName, $config))
				$config=array();
			else $config = $config[$requestedName];
			$adapter = new self::$invokableClasses[$requestedName]( $config );
			$queue = new Queue( $adapter );
			return $queue;
		}
		throw new \Exception("$requestedName implementation of queues is not supported");

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
		if (!isset($config[self::$configKey])) {
			$this->config = array();
			return $this->config;
		}

		$this->config = $config[$this->configKey];
		return $this->config;
	}
}
