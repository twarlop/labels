<?php
require_once('../handelaars2/controller/handelaar.php');
require_once('../handelaars2/vendor/autoload.php');


$provider = new ProductLabels\ProductLabelProvider(477, ProductLabels\Map::$layouts[$SETTINGS['label_type']->getValue()]);
$provider->downloadPdf();

