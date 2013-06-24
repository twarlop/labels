<?php

if(is_file('../controller/handelaar.php')){
	require_once('../controller/handelaar.php');
}

if(is_file('../vendor/twarlop/productlabels/bootstrap/start.php')){
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

$provider = new ProductLabels\ProductLabelProvider($HANDELAARID);


switch($action){
	case 'suggestCategory':
		$categories = $provider->suggestCategory($_GET['query']);
		echo $categories->toJson();
	break;

	case 'loadCategory':
		$answer = $provider->loadForInspect($_GET['categoryId']);
		echo json_encode($answer);
	break;

	case 'saveCategory':

		$categoryId = $_POST['categoryId'];
		$properties = isset($_POST['properties']) ? $_POST['properties'] : array();
		$type = $_POST['type'];
		$provider->sync($categoryId, $properties, $type);

	break;

	case 'suggestProduct':
		$products = $provider->suggestProducts($_GET['query']);
		echo json_encode($products);
	break;

	case 'addProduct':
		$prodid = $_POST['prodid'];
		if(isset($_POST['datum']))
		{
			$datum = DateTime::createFromFormat('d/m/Y', $_POST['datum']);
		}
		else
		{
			$datum = new DateTime();
		}
		$product = $provider->queue($prodid, $datum);
		echo $product->toJson();
	break;

	case 'removeProduct':
		$provider->dequeue($_POST['prodid']);
	break;

	case 'reloadProduct':
		$prodid = intval($_GET['product-id']);
		$datum = DateTime::createFromFormat('d/m/Y', $_GET['datum']);
		$product = $provider->reloadProduct($prodid, $datum);
		echo $product->toJson();
	break;

	case 'clearQueue':
		$provider->clearQueue();
	break;

	case 'saveMijnTekst':
		$prodid = intval($_POST['prodid']);
		$nl = isset($_POST['tekstnl']) ? $_POST['tekstnl'] : '';
		$fr = isset($_POST['tekstfr']) ? $_POST['tekstfr'] : '';
		$provider->customiseText($prodid, $nl, $fr);
	break;

	case 'loadBaseText':
		$type = intval($_POST['type']);
		$productid = intval($_POST['prodid']);
		$text = $provider->loadCustomText($productid, $type);
		echo $text;
	break;

}