<?php

namespace ProductLabels\Setup;

use ProductLabels\Contract\ProviderInterface;
use ProductLabels\DB;

/**
* LabelProductProvider
*
* provider class to fetching products and their prices etc
*/
class LabelProductProvider implements ProviderInterface
{
	
	protected $handelaarid;

	protected $connection;

	public function __construct($handelaarid)
	{
		$this->handelaarid = $handelaarid;
		$this->connection = DB::connection('sos');
	}

	public function find(array $prodids)
	{
		if(count($prodids) === 0)
			return array();
		$query = $this->connection->table('prod');
		$products = $query->join('merken', 'merken.merkid', '=', 'prod.Brand')
			->join('fabrikanten', 'merken.merkid', '=', 'fabrikanten.merkid')
			->whereIn('prod.ID', $prodids)
			->get(array('prod.ID as product_id','ArtName_nl as title', 'merken.merk_nl as merknaam', 'merken.merkid', 'prod.Image_small as photo', 'fabrikanten.logo_small as logoMerk', 'prod.primairecatid as categorie_id'));
		$products = $this->instantiate($products);
		return $products;
	}

	protected function instantiate(array $products)
	{
		$results = array();
		foreach($products as $product)
		{
			$product = new LabelProduct($product);
			$results[$product->product_id] = $product;
		}
		return $results;
	}
}