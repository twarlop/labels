<?php
namespace ProductLabels;

use ProductLabels\Contract\ProviderInterface;

class ProductLabelProvider implements ProviderInterface{

	protected $handelaarid;

	public function __construct($handelaarid)
	{
		$this->handelaarid = $handelaarid;
	}

}