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
}