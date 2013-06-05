<?php
/**
 * manually include autoload to simplify installation in old sos system
 */

include __DIR__ . '/../bootstrap/start.php';

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
<p>
	<label for="etiketCategorieSearch">
		Bewerk Categorie
	</label>
	<input id='etiketCategorieSearch' type='text'>
</p>

<div id="primary-app">
	<h5>Print your labels</h5>
	<div>
		hier worden de labels geprint.
	</div>
</div>

<div id='propertyPicker' class='clearfix' style='display:none'>
	<a href="#" class="close-app"><i class='ui-icon ui-icon-close'>&nbsp;</i></a>
	<div id="action-holder">
		<a href="#" class="button submit-properties">Bevestigen</a>
		<a href="#" class="button reset-properties">Ongedaan maken</a>
		<a href="#" class="button full-reset-properties">Volledige reset</a>
	</div>
	<div class='left'>
		<h5>Gebruik</h5>
		<ul id="addedContainer"></ul>
	</div>
	<div class='left'>
		<h5>Niet gebruiken</h5>
		<ul id="addableContainer"></ul>
	</div>
	<div class='left'>
		<h5>Standaard volgorde</h5>
		<ul id='standard'></ul>
	</div>
</div>



