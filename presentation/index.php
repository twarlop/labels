<?php
/**
 * manually include autoload to simplify installation in old sos system
 */

include __DIR__ . '/../bootstrap/start.php';


setLocale(LC_MONETARY, 'nl_BE');

// include('tests/classes.php');

// $migration = new ProductLabels\Migration\Dimensions();
// $migration->run();
// $migration = new ProductLabels\Migration\Queue();
// $migration->run();

// $migration = new ProductLabels\Migration\Properties();
// $migration->run();


$provider = new ProductLabels\ProductLabelProvider(477);
$products = $provider->fetchProducts();

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
		<h5>Stap 1</h5>
		<p>
			<label for="etiketAfmeting">Afmeting</label>
			<select name="etiketAfmeting" id="etiketAfmeting">
				<option value="">Kies een afmeting</option>
			</select>

			<br>

			<label for="etiketType">Soort inhoud</label>
			<select name="etiketType" id="etiketType">
				<option value="1">Volledig tekst</option>
				<option value="2">Eigenschappen</option>
				<option value="3">Korte tekst</option>
			</select>

			<br>

			<label for="etiketLang">Taal</label>
			<select name="etiketLang" id="etiketLang">
				<option value="1">Nederlands</option>
				<option value="2">Frans</option>
			</select>

			<br>

			<label for="etiketDatum">Gebruik prijzen geldig op:	</label>
			<input type="text" id='etiketDatum' value=''>

		</p>
	</div>
	<div id='step2'>
		<h5>stap 2</h5>
		<p>
			<table>
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
						</td>
						<td>
							<?= $product->category ?><br>
							<a href='#' class='inspect'><?= $product->title ?></a>
						</td>
						<td>
							<?= $product->merknaam ?>
						</td>
						<td><?= money_format('%n', $product->prijs->prijs) ?></td>
						<? if($product->promotie): ?>
							<td><?= money_format('%n', $product->promotie->promo) ?></td>
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
						<td></td>
						<td></td>
					</tr>
					<? endforeach ?>
				</tbody>
				<tfoot>
					<tr class='template-row'>
						<td>
						</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
				</tfoot>
			</table>
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



