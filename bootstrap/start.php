<?php
if(is_file(__DIR__. '/../vendor/autoload.php')){
	require __DIR__. '/../vendor/autoload.php';
}
else if(is_file(rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/handelaars2/vendor/autoload.php')){
	require rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/handelaars2/vendor/autoload.php';
}

//setup laravel connection
$settings = array(
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => DB_SOS,
    'username' => DB_SOS_USER,
    'password' => DB_SOS_PASSWORD,
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => ''
);

$container = new Illuminate\Container\Container();

$connFactory = new \Illuminate\Database\Connectors\ConnectionFactory($container);
$conn = $connFactory->make($settings);

//set event dispatcher
$eventDispatcher = new Illuminate\Events\Dispatcher();
// $eventDispatcher->listen('illuminate.query', function($query, $bindings, $time)
// {
//     echo '<div style=\'background-color:#ddd;margin: 5px;\'>';
//     echo $query;
//     echo '<pre>';
//     print_r($bindings);
//     echo '</pre>';
//     echo '</div>';
// });

$conn->setEventDispatcher($eventDispatcher);

ProductLabels\DB::addConnection('sos', $conn);


/**
 * Uncomment to use eloquent
 */

$resolver = new \Illuminate\Database\ConnectionResolver();
$resolver->addConnection('sos', $conn);
$resolver->setDefaultConnection('sos');


Illuminate\Database\Eloquent\Model::setConnectionResolver($resolver);