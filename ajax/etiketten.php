<?php

include '../bootstrap/start.php';

$action = false;
if(isset($_GET['action']))
{
	$action = $_GET['action'];
}
else if( isset($_POST['action']))
{
	$action = $_POST['action'];
}


$categoryProvider = new ProductLabels\Categories\CategoryProvider();
$propertyProvider = new ProductLabels\Properties\PropertyProvider(477);


switch($action){
	case 'suggestCategory':
		$categories = $categoryProvider->suggest($_GET['query']);
		echo $categories->toJson();
	break;

	case 'loadCategory':
		$standardProperties = $propertyProvider->fetchStandardPropertyOrder($_GET['categoryId']);
		$customProperties = $propertyProvider->fetchCustomPropertyOrder($_GET['categoryId']);
		echo json_encode(array(
			'standard' => $standardProperties->toArray(),
			'custom' => $customProperties->toArray()
		));
	break;
}