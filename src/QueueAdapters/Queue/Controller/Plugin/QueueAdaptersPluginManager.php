<?php
/**
 * Queue Adapter Module for Zend Framework
 *
 * @link      https://github.com/joachimdo/zf2-queueing-factory-module 
 * @copyright Copyright (c) 2014 Joachim Dornbusch
 * @license   GNU-GPL V3+
 */

namespace QueueAdapters\Controller\Plugin;



use Zend\Mvc\Controller\PluginManager;
/**
 * Queue adapters factory for different implementations.
 */
class QueueAdaptersPluginManagerFactory extends PluginManager
{
	/**
	 * Default set of plugins factories
	 *
	 * @var array
	 */
	protected $factories = array();
	
	/**
	 * Default set of plugins
	 *
	 * @var array
	*/
	protected $invokableClasses = array(
			'acceptableviewmodelselector' => 'Zend\Mvc\Controller\Plugin\AcceptableViewModelSelector',
			'filepostredirectget'         => 'Zend\Mvc\Controller\Plugin\FilePostRedirectGet',
			'flashmessenger'              => 'Zend\Mvc\Controller\Plugin\FlashMessenger',
			'layout'                      => 'Zend\Mvc\Controller\Plugin\Layout',
			'params'                      => 'Zend\Mvc\Controller\Plugin\Params',
			'postredirectget'             => 'Zend\Mvc\Controller\Plugin\PostRedirectGet',
			'redirect'                    => 'Zend\Mvc\Controller\Plugin\Redirect',
			'url'                         => 'Zend\Mvc\Controller\Plugin\Url',
	);
	
	/**
	 * Default set of plugin aliases
	 *
	 * @var array
	*/
	protected $aliases = array(
			'prg'     => 'postredirectget',
			'fileprg' => 'filepostredirectget',
	);
	
	/**
	 * @var DispatchableInterface
	*/
	protected $controller;
}
