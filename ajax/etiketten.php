<?php
error_reporting(-1);
ini_set('display_errors', 'on');
if(is_file('../bootstrap/start.php'))
	include '../bootstrap/start.php';
else
{
	include '../vendor/twarlop/productlabels/bootstrap/start.php';
}

$action = false;
if(isset($_GET['action']))
{
	$action = $_GET['action'];
}
else if( isset($_POST['action']))
{
	$action = $_POST['action'];
}

$provider = new ProductLabels\ProductLabelProvider(477);


switch($action){
	case 'suggestCategory':
		$categories = $provider->suggestCategory($_GET['query']);
		echo $categories->toJson();
	break;

	case 'loadCategory':
		$answer = $provider->fetchSortingsForCategory($_GET['categoryId']);
		echo json_encode($answer);
	break;

	case 'saveCategory':

		$categoryId = $_POST['categoryId'];
		$properties = isset($_POST['properties']) ? $_POST['properties'] : array();
		$provider->sync($categoryId, $properties);

	break;

	case 'suggestProduct':
		$products = $provider->suggestProducts($_GET['query']);
		echo json_encode($products);
	break;

	case 'addProduct':
		$prodid = $_POST['prodid'];
		$product = $provider->queue($prodid);
		echo $product->toJson();
	break;

	case 'removeProduct':
		$provider->dequeue($_POST['prodid']);
	break;

	case 'reloadProduct':
		$prodid = intval($_GET['product-id']);
		$product = $provider->reloadProduct($prodid);
		echo $product->toJson();
	break;

	case 'clearQueue':
		$provider->clearQueue();
	break;

}