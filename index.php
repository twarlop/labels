<?php
include('bootstrap/start.php');
include('tests/classes.php');

// $migration = new ProductLabels\Migration\Dimensions();
// $migration->run();
// $migration = new ProductLabels\Migration\Queue();
// $migration->run();

// $migration = new ProductLabels\Migration\Properties();
// $migration->run();


// $provider = new ProductLabels\ProductLabelProvider(477);
// $products = $provider->fetchProducts();
// var_dump($products);


//start navigation

$page = isset($_GET['page']) ? $_GET['page'] : 'home';
?>
<ul>
	<li><a href='?page=home'>Home</a></li>
	<li><a href='?page=categories'>Categories</a></li>
</ul>
<?


switch($page)
{
	case 'home':
		include('views/home.php');
	break;
	case 'categories':
		include('views/categories.php');
	break;
}
