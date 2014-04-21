<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace QueueAdapters\Queue\Service;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZendQueue\Adapter\Activemq;
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
	protected static $adapterTypes = array("activemq");


	/**
	 * @param  ServiceLocatorInterface $services
	 * @param  string                  $name
	 * @param  string                  $requestedName
	 * @return bool
	*/
	public function canCreateServiceWithName(ServiceLocatorInterface $services, $name, $requestedName)
	{
		return (in_array($requestedName, self::$adapterTypes));
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
		switch ($requestedName) {
			case 'activemq':
				if(!array_key_exists('activemq', $config))
					$config=array();
				else $config = $config['activemq'];
				$adapter = new Activemq( $config );
				$queue = new Queue( $adapter );
				break;
			default:
				throw new \Exception("$requestedName implementation of queues is not supported");
				break;
		}
		return $queue;
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
