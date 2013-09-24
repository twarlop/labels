<?php
if(is_file(rtrim(__DIR__, '/'). '/../vendor/autoload.php')){
	require rtrim(__DIR__, '/'). '/../vendor/autoload.php';
}
else if(is_file(rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/handelaars2/vendor/autoload.php')){
	require rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/handelaars2/vendor/autoload.php';
}

//setup laravel connection
if(preg_match('/local\.sos/', $_SERVER['HTTP_HOST']))
{
    $settings = array(
        'driver' => 'mysql',
        'host' => 'localhost',
        'database' => 'shoponsite',
        'username' => 'root',
        'password' => 'root',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => ''
    );
}
else{
    $settings = array(
        'driver' => 'mysql',
        'host' => 'localhost',
        'database' => 'elektro_sos',
        'username' => 'ez_sos',
        'password' => 'bert872',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => ''
    );

}



$container = new Illuminate\Container\Container();

$connFactory = new \Illuminate\Database\Connectors\ConnectionFactory($container);
$conn = $connFactory->make($settings);

//set event dispatcher
$eventDispatcher = new Illuminate\Events\Dispatcher();

$conn->setEventDispatcher($eventDispatcher);

ProductLabels\DB::addConnection('sos', $conn);


/**
 * Uncomment to use eloquent
 */

$resolver = new \Illuminate\Database\ConnectionResolver();
$resolver->addConnection('sos', $conn);
$resolver->setDefaultConnection('sos');


Illuminate\Database\Eloquent\Model::setConnectionResolver($resolver);