<?php
/**
 * manually include autoload to simplify installation in old sos system
 */

include __DIR__ . '/../bootstrap/start.php';


setLocale(LC_MONETARY, 'nl_BE');

// include('tests/classes.php');

/**
 * UNCOMMENT THE FOLLOWING TO RUN THE MIGRATION
 */

// $migration = new ProductLabels\Migration\Dimensions();
// $migration->run();
// $migration = new ProductLabels\Migration\Queue();
// $migration->run();

// $migration = new ProductLabels\Migration\Properties();
// $migration->run();

// exit();


$provider = new ProductLabels\ProductLabelProvider(477);
$products = $provider->fetchProducts();

$afmetingen = $provider->fetchAfmetingen();

$today = new DateTime();

$page = isset($_GET['page']) ? $_GET['page'] : 'home';
?>
<p>
	<label for="etiketCategorieSearch">
		Bewerk Categorie
	</label>
	<input id='etiketCategorieSearch' type='text'>
</p>

<div id="primary-app">
	<div id='step1'>
		<h3>Stap 1</h3>
		<p id='optionsEtiket'>
			<label for="etiketAfmeting">Afmeting</label>
			<select name="etiketAfmeting" id="etiketAfmeting">
				<option value="">Kies een afmeting</option>
				<? foreach($afmetingen as $afmeting): ?>
				<option value="<?= $afmeting->id ?>"><?= $afmeting->name ?></option>
				<? endforeach; ?>
			</select>

			<br>

			<label for="etiketType">Soort inhoud</label>
			<select name="etiketType" id="etiketType">
				<option value="1" <?= $SETTINGS['label_mode']->getValue() === '1' ? 'selected' : ''?>>Volledig tekst</option>
				<option value="2" <?= $SETTINGS['label_mode']->getValue() === '2' ? 'selected' : ''?>>Eigenschappen</option>
				<option value="3" <?= $SETTINGS['label_mode']->getValue() === '3' ? 'selected' : ''?>>Korte tekst</option>
			</select>

			<br>

			<label for="etiketLang">Taal</label>
			<select name="etiketLang" id="etiketLang">
				<option value="1" <?= $SETTINGS['label_taal']->getValue() === '1' ? 'selected' : '' ?>>Nederlands</option>
				<option value="2" <?= $SETTINGS['label_taal']->getValue() === '2' ? 'selected' : '' ?>>Frans</option>
			</select>

			<br>

			<label for="etiketDatum">Gebruik prijzen geldig op:	</label>
			<input type="text" id='etiketDatum' value='<?= $today->format('d/m/Y') ?>'>

		</p>
	</div>
	<div>
		implementeren van de zoek functie aanpassen zodat het de juiste voorwaarden gebruikt. nu wordt er nog geen rekening
		gehouden met het al dan niet klant zijn van shoponsite en dergelijke.
	</div>
	<div id='step2'>
		<h3>stap 2</h3>
		<label for="queueProduct">Product zoeken</label>
		<input type="text" id="queueProduct" placeholder='referentie'/>
		<p>
			<a href='#' class="button emptyQueue">Queue leegmaken</a>
			<table id='queueTable'>
				<thead>
					<tr>
						<th>foto</th>
						<th>artikelnaam</th>
						<th>merk</th>
						<th>prijs</th>
						<th>promoprijs</th>
						<th>promo tot</th>
						<th>eigen label</th>
						<th></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<? foreach($products as $product): ?>
					<tr data-prodid='<?= $product->product_id ?>'>
						<td>
							<img src="/images/ez_prod/<?= $product->merkid?>/<?= $product->product_id?>/tn1/<?= $product->photo?>">
						</td>
						<td>
							<a href='#' class='inspectCategory' data-category-id='<?= $product->category_id ?>'><?= $product->category ?></a><br>
							<a href='#' class='inspect'><?= $product->title ?></a>
						</td>
						<td>
							<?= $product->merknaam ?>
						</td>
						<td>
							<? if($product->prijs):
							echo '&euro;&nbsp;' . $product->prijs->prijs;
							endif; ?>
						</td>
						<? if($product->promotie): ?>
							<td><?='&euro;&nbsp;' . $product->promotie->promo;?></td>
							<td><?= $product->promotie->stop?></td>
						<? else: ?>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						<? endif; ?>
						<td>
							<? if($product->hasCustomLabel()) : ?>
							<img src="/images/bo/icons/tick.png" alt="V">
							<? endif; ?>
						</td>
						<td><img class='customise' src='/images/bo/icons/label_icon.gif'/></td>
						<td><img class='dequeue' src='/images/bo/icons/cross.png'/></td>
					</tr>
					<? endforeach ?>
				</tbody>
			</table>
		</p>
		<p>
			<a href="#" class='downloadPdf button'>Download pdf</a>
		</p>
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
		<h3>Gebruik</h3>
		<ul id="addedContainer"></ul>
	</div>
	<div class='left'>
		<h3>Niet gebruiken</h3>
		<ul id="addableContainer"></ul>
	</div>
	<div class='left'>
		<h3>Standaard volgorde</h3>
		<ul id='standard'></ul>
	</div>
</div>



