<?php

require_once('handelaars2/vendor/autoload.php');

$provider = new ProductLabels\ProductLabelProvider(477);
$provider->downloadPdf();

