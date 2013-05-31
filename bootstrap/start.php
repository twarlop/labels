<?php

//setup laravel connection
$settings = array(
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => 'elektrozine',
    'username' => 'root',
    'password' => 'root',
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => ''
);

$container = new Illuminate\Container\Container();

$connFactory = new \Illuminate\Database\Connectors\ConnectionFactory($container);
$conn = $connFactory->make($settings);

$resolver = new \Illuminate\Database\ConnectionResolver();
$resolver->addConnection('sos', $conn);
$resolver->setDefaultConnection('sos');


Illuminate\Database\Eloquent\Model::setConnectionResolver($resolver);