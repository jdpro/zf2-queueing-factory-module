<?php
/**
 * Queue Adapter Module for Zend Framework
 *
 * @link      https://github.com/joachimdo/zf2-queueing-factory-module
 * @copyright Copyright (c) 2014 Joachim Dornbusch
 * @license   GNU-GPL V3+
 */

namespace QueueAdapters\Queue\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use ZendQueue\Queue;

class QueuePlugin extends AbstractPlugin{

	private $queue;

	public function __construct(Queue $queue) {
		$this->queue=$queue;
	}
	public function __call($methodName, $args) {
		if(method_exists($this->queue, $methodName))
			return call_user_func_array(array($this->queue, $methodName), $args);
		else throw new \Exception("The Queue interface does not support the method $methodName");
	}
}