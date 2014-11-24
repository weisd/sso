<?php

return array(
	'default' => 'mysql',

	'connections' => array(

		'mysql' => array(
			'driver' => 'mysql',
			'host' => 'localhost',
			'database' => 'sns',
			'username' => 'sns',
			'password' => 'sns',
			'charset' => 'utf8',
			'collation' => 'utf8_general_ci',
			'prefix' => '',
		),
	),

	'redis' => array(

		'cluster' => false,

		'default' => array(
			'host' => '127.0.0.1',
			'port' => 6379,
			'database' => 0,
		),

	),
)
?>