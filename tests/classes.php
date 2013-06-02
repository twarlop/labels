<?php
namespace ProductLabels;

echo "start test classes<br/>";

$dimension = new Dimension\Dimension();
$dimensionprovider = new Dimension\DimensionProvider();
$photoDimension = new Dimension\PhotoDimension();
$textDimension = new Dimension\TextDimension();

$overview = new Manager\Overview();
$search = new Manager\Search();

$dimensions = new Migration\Dimensions();

$page = new Pages\Page();
$pageCollection = new Pages\PageCollection();
$pageProvider = new Pages\PageProvider();

$labelProduct = new Setup\LabelProduct();
$labelProductProvider = new Setup\LabelProductProvider(477);

$document = new Document();
$layout = new Label\Layout();
$productLabelProduct = new ProductLabelProvider(477);

echo "end test classes<br/>";
