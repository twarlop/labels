<?php
namespace ProductLabels;

echo "start test classes<br/>";

$dimension = new Dimension\Dimension();
$dimensionFactory = new Dimension\DimensionFactory();
$dimensionprovider = new Dimension\DimensionProvider();
$photoDimension = new Dimension\PhotoDimension();
$textDimension = new Dimension\TextDimension();

$overview = new Manager\Overview();
$search = new Manager\Search();

$dimensions = new Migration\Dimensions();

$page = new Pages\Page();
$pageCollection = new Pages\PageCollection();
$pageFactory = new Pages\PageFactory();

$labelProduct = new Setup\LabelProduct();
$labelProductProvider = new Setup\LabelProductProvider();

$document = new Document();
$layout = new Layout();
$productLabelProduct = new ProductLabelProvider();


echo "end test classes<br/>";
