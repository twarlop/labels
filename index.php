<?php
include('bootstrap/start.php');
include('tests/classes.php');

$migration = new ProductLabels\Migration\Dimensions();
$migration->run();
$migration = new ProductLabels\Migration\Queue();
$migration->run();



