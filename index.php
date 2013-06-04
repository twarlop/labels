<?php
/**
 * manually include autoload to simplify installation in old sos system
 */
require __DIR__. '/../vendor/autoload.php';

include('bootstrap/start.php');
// include('tests/classes.php');

// $migration = new ProductLabels\Migration\Dimensions();
// $migration->run();
// $migration = new ProductLabels\Migration\Queue();
// $migration->run();

// $migration = new ProductLabels\Migration\Properties();
// $migration->run();


// $provider = new ProductLabels\ProductLabelProvider(477);
// $products = $provider->fetchProducts();
// var_dump($products);


$page = isset($_GET['page']) ? $_GET['page'] : 'home';
?>
<html>
	<head>
		<link rel="stylesheet" href="css/ui.css">
		<link rel="stylesheet" href="css/etiketten.css">
	</head>
	<body>
		<div>
			<label for="etiketCategorieSearch">
				Bewerk Categorie
			</label>
			<input id='etiketCategorieSearch' type='text'>
		</div>

		<div id='propertyPicker'>
			<div id="action-holder">
				<a href="#" class="button submit-properties">Bevestigen</a>
				<a href="#" class="button reset-properties">Ongedaan maken</a>
				<a href="#" class="button full-reset-properties">Volledige reset</a>
			</div>
			<div class='left'>
				<ul id="addedContainer"></ul>
			</div>
			<div class='right'>
				<ul id="addableContainer"></ul>
			</div>
		</div>

		<script src='js/jquery-1.8.3.js'></script>
		<script src='js/jquery-ui-1.9.2.custom.min.js'></script>
		<script src='js/etiketten.js'></script>
	</body>
</html>



