<?php

namespace ProductLabels\Setup\Promoties;

use DateTime;

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
			if(method_exists($this, 'get'.ucfirst($name)))
			{
				return $this->{'get' . ucfirst($name)}();
			}
			return $this->$name;
		}
	}

	public function getStop(){
		if($this->stop)
		{
			$stop = DateTime::createFromFormat('Y-m-d', $this->stop);
			return $stop->format('d/m/Y');
		}
	}

	public function toJson()
	{
		$properties = get_object_vars($this);
		$json = array();
		foreach($properties as $property => $value)
		{
			if(method_exists($this, 'get'.ucfirst($name)))
			{
				$value = $this->{'get' . ucfirst($name)}();
			}
			$json[$property] = $value;
		}
		return $json;
	}
}