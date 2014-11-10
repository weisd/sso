<?php

return array(
	'default' => 'redis',

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
			'host' => '10.6.9.6',
			'port' => 6379,
			'database' => 0,
		),

	),
)
?>