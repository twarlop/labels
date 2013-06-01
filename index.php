<?php
include('bootstrap/start.php');
include('tests/classes.php');

$connection = ProductLabels\DB::connection('sos');
$query = $connection->table('sos_labels');




$migration = new ProductLabels\Migration\Dimensions();
$migration->run();



