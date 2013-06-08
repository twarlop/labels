<?php

namespace ProductLabels\Setup\Promoties;

/**
* Promotie
*/
class Promotie
{
	protected $prodid;
	protected $type;
	protected $owner;
	protected $start;
	protected $stop;
	protected $promo;


	public function __construct(array $data = array())
	{
		foreach($data as $k => $v)
		{
			if(property_exists($this, $k))
			{
				$this->$k = $v;
			}
		}
	}

	public function __get($name)
	{
		if(property_exists($this, $name))
		{
			return $this->$name;
		}
	}

	public function toJson()
	{
		$properties = get_object_vars($this);
		$json = array();
		foreach($properties as $property => $value)
		{
			$json[$property] = $value;
		}
		return $json;
	}
}