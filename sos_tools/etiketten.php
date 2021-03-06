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
		'label_type2' => new Setting(1),
		'label_taal2' => new Setting(1),
		'label_mode2' => new Setting(1)
	];
}
if(is_file('../handelaars2/vendor/autoload.php'))
	require_once('../handelaars2/vendor/autoload.php');
else
	require_once('../vendor/autoload.php');

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
$provider->downloadPdf($datum);

