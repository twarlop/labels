<?php
if(is_file('../handelaars2/controller/handelaar.php'))
	require_once('../handelaars2/controller/handelaar.php');
else
{
	
class Setting
{
	protected $value;

	public function __construct($value)
	{
		$this->value = $value;
	}

	public function setValue($value)
	{
		$this->$value = $value;
	}

	public function getValue()
	{
		return $this->value;
	}
}

$SETTINGS = [
	'label_type' => new Setting(1),
	'label_taal' => new Setting(1),
	'label_mode' => new Setting(1)
];
}
if(is_file('../handelaars2/vendor/autoload.php'))
	require_once('../handelaars2/vendor/autoload.php');
else
	require_once('../vendor/autoload.php');




$provider = new ProductLabels\ProductLabelProvider(477, ProductLabels\Map::$layouts[$SETTINGS['label_type']->getValue()]);
$provider->downloadPdf();
