<?php
namespace ProductLabels\Properties;

use ProductLabels\Contract\ProviderInterface;

/**
* PropertyProvider
*/
class PropertyProvider implements ProviderInterface
{

	protected $handelaar_id;

	public function __construct($handelaarid)
	{
		$this->handelaar_id = $handelaarid;
	}
	
}