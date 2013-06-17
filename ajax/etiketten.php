<?php
error_reporting(-1);
ini_set('display_errors', 'on');
if(is_file('../bootstrap/start.php'))
	include '../bootstrap/start.php';
else
{
	include '../vendor/twarlop/productlabels/bootstrap/start.php';
}

if(is_file('../handelaars2/controller/handelaar.php'))
	require_once('../handelaars2/controller/handelaar.php');
else
{
	
class Setting
{
	protected $value;

	public function __construct($value)
	{
		$this->value = $value;
	}

	public function setValue($value)
	{
		$this->$value = $value;
	}

	public function getValue()
	{
		return $this->value;
	}
}

$SETTINGS = [
	'label_type' => new Setting(1),
	'label_taal' => new Setting(1),
	'label_mode' => new Setting(1)
];
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

}