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
<div>
	<label for="etiketCategorieSearch">
		Bewerk Categorie
	</label>
	<input id='etiketCategorieSearch' type='text'>
</div>

<div id='propertyPicker' class='clearfix'>
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



