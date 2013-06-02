<?php

namespace ProductLabels\Setup;

/**
* LabelProduct
*
* This is supposed to be a class that will fetch the products that need to be printed
* We get them out of the print queue that is defined by the handelaar
*/
class LabelProduct
{
	
	protected $product_id;
	protected $merkid;
	protected $merknaam;
	protected $categorie_id;
	protected $title;
	protected $photo;
	protected $prijs;
	protected $promotie;
	protected $promotietext;
	protected $text;
	protected $logoMerk;
	protected $logoHandelaar;


	public function __construct(array $data = array())
	{
		foreach($data as $k => $v)
		{
			$this->$k = $v;
		}
	}

	public function __get($name)
	{
		return $this->$name;
	}

}