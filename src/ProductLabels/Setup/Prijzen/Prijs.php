<?php
namespace ProductLabels\Setup\Prijzen;


/**
* Prijs
*/
class Prijs
{

	protected $owner;
	protected $type;
	protected $prijs;
	protected $prodid;

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