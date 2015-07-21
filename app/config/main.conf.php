<?php

$config = array(
    'debug' => true,
    'monolog' => array(
        'path' => __DIR__.'/../../storage/logs/monolog.log'
    ),
    'twig' => array(
        'path' => __DIR__.'/../views'
    ),
    'capsule_connection' => array(
        'driver'    => 'mysql',
        'host'      => 'localhost',
        'database'  => 'currency_fair',
        'username'  => '',
        'password'  => '',
        'charset'   => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix'    => '',
    )
);

return $config;
