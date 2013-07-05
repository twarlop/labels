<?php
//mock settings here, so we can use them in our dev environment



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
?>
<html>
	<head>
		<link rel="stylesheet" href="css/ui.css">
		<link rel="stylesheet" href="css/etiketten.css">
	</head>
	<body>
		<? include('presentation/index.php') ?>
		<script src='js/jquery-1.8.3.js'></script>
		<script src='js/jquery-ui-1.9.2.custom.min.js'></script>
		<script src='js/etiketten.js'></script>
	</body>
</html>