zf2-queueing-factory-module
===========================
This module only provides an abstract factory in order to give access to the queueing functionalities via zend framework 2 service manager. For now, it is limited to activemq.

Just add QueueAdapters to the listed module names, rename and copy the module.queue-adapters.local.php.dist to your application config, e.g. :

return array (
		"queue-adapters" => array(
				"activemq" => array (
						"host" => "127.0.0.1",
						"port" => "61613",
						"scheme" => "tcp"
				)
		)

);
