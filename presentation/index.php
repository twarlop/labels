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

// $migration = new ProductLabels\Migration\CategoryTypes();
// $migration->run();

// $migration = new ProductLabels\Migration\Dimensions();
// $migration->run();

// $migration = new ProductLabels\Migration\Queue();
// $migration->run();

// $migration = new ProductLabels\Migration\Properties();
// $migration->run();

if(isset($_GET['datum']))
{
	$datum = $_GET['datum'];
	$datum = DateTime::createFromFormat('d/m/Y', $datum);
}
else
{
	$datum = new DateTime();
}

$provider = new ProductLabels\ProductLabelProvider($HANDELAARID);
$products = $provider->fetchProducts($datum);
$afmetingen = $provider->fetchAfmetingen();

$page = isset($_GET['page']) ? $_GET['page'] : 'home';
?>
<p>
	<label for="etiketCategorieSearch">
		<?= trans('etiket.edit_cat'); ?>
	</label>
	<input id='etiketCategorieSearch' type='text'>
</p>

<div id="primary-app">
	<div id='step1'>
		<h3><?= trans('etiket.stap_1')?></h3>
		<div id='optionsEtiket'>
			<p>
				<label for="etiketAfmeting"><?= trans('etiket.label') ?></label>
				<select name="etiketAfmeting" id="etiketAfmeting">
					<option value=""><?= trans('etiket.kies_label') ?></option>
					<? foreach($afmetingen as $afmeting): ?>
					<option value="<?= $afmeting->id ?>" <?= $SETTINGS['label_type2']->getValue() == $afmeting->id ? 'selected' : '' ?>><?= $afmeting->name ?></option>
					<? endforeach; ?>
				</select>
			</p>
			
			<p>
				<label for="etiketType"><?= trans('etiket.type_content') ?></label>
				<select name="etiketType" id="etiketType">
					<option value=""><?= trans('etiket.kies_content') ?></option>
					<option value="1" <?= $SETTINGS['label_mode2']->getValue() === '1' ? 'selected' : ''?>><?= trans('etiket.tekst') ?></option>
					<option value="2" <?= $SETTINGS['label_mode2']->getValue() === '2' ? 'selected' : ''?>><?= trans('etiket.eigenschappen') ?></option>
				</select>
			</p>

			<p>
				<label for="etiketLang"><?= trans('etiket.taal') ?></label>
				<select name="etiketLang" id="etiketLang">
					<option value=""><?= trans('etiket.kies_taal') ?></option>
					<option value="1" <?= $SETTINGS['label_taal2']->getValue() === '1' ? 'selected' : '' ?>><?= trans('etiket.nederlands') ?></option>
					<option value="2" <?= $SETTINGS['label_taal2']->getValue() === '2' ? 'selected' : '' ?>><?= trans('etiket.frans') ?></option>
				</select>
			</p>

			<p>
				<label for="etiketDatum"><?= trans('etiket.date_pick') ?></label>
				<input type="text" id='etiketDatum' value='<?= $datum->format('d/m/Y') ?>'>
			</p>

			<p>
				<label for='etiketDisclaimerNl'><?= trans('etiket.auto_text_nl') ?></label>
				<input type="text" id='etiketDisclaimerNl' value='<?= $SETTINGS['label_disclaimer_nl']->getValue() ?>'/>
			</p>
			<p>
				<label for='etiketDisclaimerFr'><?= trans('etiket.auto_text_fr') ?></label>
				<input type="text" id='etiketDisclaimerFr' value='<?= $SETTINGS['label_disclaimer_fr']->getValue() ?>'/>
			</p>

		</div>
	</div>
	<div id='step2'>
		<h3><?= trans('etiket.stap_2') ?></h3>
		<label for="queueProduct"><?= trans('etiket.search_product') ?></label>
		<input type="text" id="queueProduct" placeholder='referentie'/>
		<p>
			<a href='#' class="button emptyQueue"><?= trans('etiket.empty_queue') ?></a>
			<a href="/sos_tools/etiketten.php?datum=<?= $datum->format('d/m/Y')?>" class='button'><?= trans('etiket.download') ?></a>
			<a href="/sos_tools/qrcodes.php?hid=<?= $HANDELAARID?>" class='button' target="_blank"><?= trans('etiket.download_qr') ?></a>
		</p>
			
			<table id='queueTable'>
				<thead>
					<tr>
						<th><?= trans('etiket.photo') ?></th>
						<th><?= trans('etiket.article')?></th>
						<th><?= trans('etiket.brand') ?></th>
						<th><?= trans('etiket.price') ?></th>
						<th><?= trans('etiket.promotion') ?></th>
						<th><?= trans('etiket.promotion_stop') ?></th>
						<th><?= trans('etiket.custom_label') ?></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<? foreach($products as $product): ?>
					<tr data-prodid='<?= $product->product_id ?>'>
						<td align='center'>
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
                            <a class="button removeCustomLabel"><?= trans('etiket.remove_custom_label') ?></a>
							<? endif; ?>
						</td>
						<td><img class='dequeue' src='/images/bo/icons/cross.png'/></td>
					</tr>
					<? endforeach ?>
				</tbody>
			</table>

	</div>
</div>

<div id='propertyPicker' class='clearfix' style='display:none'>
	<a href="#" class="close-app"><i class='ui-icon ui-icon-close'>&nbsp;</i></a>
	<div id="action-holder">
		<a href="#" class="button submit-properties"><?= trans('etiket.confirm') ?></a>
		<a href="#" class="button reset-properties"><?= trans('etiket.undo') ?></a>
		<a href="#" class="button full-reset-properties"><?= trans('etiket.reset') ?></a>
	</div>
	<div id='info_type_settings'>
		<p class="info"><?= trans('etiket.customise_info') ?>
		</p>
		<label for="info_type_text">
			<input type="radio" name='info_type' id='info_type_text' value='text'/>
			<?= trans('etiket.tekst') ?>
		</label>
		<label for="info_type_properties">
			<input type="radio" name='info_type' id='info_type_properties' value='properties'/>
			<?= trans('etiket.eigenschappen') ?>
		</label>
	</div>
	<div class='left'>
		<h3><?= trans('etiket.gebruik') ?></h3>
		<ul id="addedContainer"></ul>
	</div>
	<div class='left'>
		<h3><?= trans('etiket.niet_gebruiken') ?></h3>
		<ul id="addableContainer"></ul>
	</div>
	<div class='left'>
		<h3><?= trans('etiket.standaard_volgorde') ?></h3>
		<ul id='standard'></ul>
	</div>
</div>



