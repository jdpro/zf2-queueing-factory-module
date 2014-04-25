<?php
return array (
		'service_manager' => array (
				'abstract_factories' => array(
						'QueueAdapters\Queue\Service\QueueAdaptersAbstractServiceFactory',
				),
		),
		'controller_plugins' => array(
				'factories' => array(
						"queue"=>'QueueAdapters\Queue\Controller\Plugin\QueuePluginFactory',
				)
		),
);