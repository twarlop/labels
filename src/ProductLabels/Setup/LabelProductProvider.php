<?php

namespace ProductLabels\Setup;

use ProductLabels\Contract\ProviderInterface;

/**
* LabelProductProvider
*
* provider class to fetching products and their prices etc
*/
class LabelProductProvider implements ProviderInterface
{
	
	protected $handelaarid;

	public function __construct($handelaarid)
	{
		$this->handelaarid = $handelaarid;

	}
}