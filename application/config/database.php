<?php

defined('SYSPATH') OR die('No direct access allowed.');

return array
    (
        'default' => array
        (
            'type'       => 'MySQLi',
            'connection' => array(
                'hostname'   => '115.28.86.37',
                'database'   => 'hotgirl',
                'username'   => 'root',
                'password'   => '1024@fangyong',
                'persistent' => FALSE,
                'ssl'        => NULL,
            ),
            'table_prefix' => '',
            'charset'      => 'utf8',
            'caching'      => FALSE,
        ),
        'hotgirl' => array
        (
            'type'       => 'MySQLi',
            'connection' => array(
                'hostname'   => '115.28.86.37',
                'database'   => 'hotgirl',
                'username'   => 'root',
                'password'   => '1024@fangyong',
                'persistent' => FALSE,
                'ssl'        => NULL,
            ),
            'table_prefix' => '',
            'charset'      => 'utf8',
            'caching'      => FALSE,
        ),
        'core' => array
                (
 		'type'       => 'MySQLi',
 		'connection' => array(
 			'hostname'   => 'localhost',
 			'database'   => 'mkmoney',
 			'username'   => 'root',
 			'password'   => '91mob.net',
 			'persistent' => FALSE,
 			'ssl'        => NULL,
 		),
 		'table_prefix' => '',
 		'charset'      => 'utf8',
 		'caching'      => FALSE,
 	),
);
