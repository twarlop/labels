<?php

namespace ProductLabels\Setup;

use ProductLabels\Contract\ProviderInterface;
use ProductLabels\DB;
use ProductLabels\Setup\Prijzen\PrijsProvider;
use ProductLabels\Setup\Promoties\PromotieProvider;

/**
* LabelProductProvider
*
* provider class to fetching products and their prices etc
*/
class LabelProductProvider implements ProviderInterface
{
	/**
	 * Evidently this is the handelaarid ;-)
	 */
	protected $handelaarid;

	/**
	 * Connection object
	 */
	protected $connection;

	/**
	 * contains an array of ids for the valid groeperingen for this handelaar
	 */
	protected $groeperingen;


	protected $prijsProvider;
	protected $promotieProvider;
	protected $propertyProvider;
	protected $labelProvider;

	public function __construct($handelaarid, $propertyProvider, $labelProvider)
	{
		$this->handelaarid = $handelaarid;
		$this->connection = DB::connection('sos');
		$this->prijsProvider = new PrijsProvider($this->handelaarid, $this->connection, $this->groeperingen);
		$this->promotieProvider = new PromotieProvider($this->handelaarid, $this->connection, $this->groeperingen, $labelProvider);
		$this->propertyProvider = $propertyProvider;
		$this->labelProvider = $labelProvider;
	}

	public function find(array $prodids)
	{
		if(count($prodids) === 0)
			return array();
		$products = $this->findBases($prodids);
		$products = $this->instantiate($products);
		$this->groeperingen = $this->groeperingen();
		$this->prijsProvider->find($prodids, $products);
		$this->promotieProvider->find($prodids, $products);
		$this->propertyProvider->propertiesForProducts($prodids, $products);
		$this->findCustomLabels($prodids, $products);
		return $products;
	}

	public function findById($prodid)
	{
		$products = $this->find(array($prodid));
		return $products[$prodid];
	}

	protected function findBases(array $prodids)
	{
		$query = $this->connection->table('prod');
		$products = $query->join('merken', 'merken.merkid', '=', 'prod.Brand')
			->join('fabrikanten', 'merken.merkid', '=', 'fabrikanten.merkid')
			->join('categories', 'categories.ID', '=', 'prod.primairecatid')
			->whereIn('prod.ID', $prodids)
			->get(array(
				'prod.ID as product_id',
				'ArtName_nl as title', 
				'merken.merk_nl as merknaam', 
				'merken.merkid', 
				'prod.Image_small as photo',
				'fabrikanten.logo_small as logoMerk', 
				'prod.primairecatid as category_id', 
				'categories.Title_short_nl as category'
			));
		return $products;
	}

	/**
	 * Here we look for custom property order.
	 * If we don't have any, the properties use standard sort
	 */
	protected function splitProdids(array $prodids)
	{
		$query = $this->connection->table('prod');
		$customProdids = $query->join('label_category_properties as lcp', 'lcp.category_id', '=', 'prod.primairecatid')
			->whereIn('prod.ID', $prodids)
			->where('lcp.owner_id', $this->handelaarid)
			->distinct()
			->get(array('prod.ID as product_id'));
	}

	/**
	 * Finds the custom label text provided by the handelaar
	 */
	protected function findCustomLabels(array $prodids, array $products)
	{
		$query = $this->connection->table('handelaars_labels');
		$labels = $query->whereHandelaarid($this->handelaarid)
			->whereIn('prodid', $prodids)
			->get();
		foreach($labels as $label)
		{
			if(isset($products[$label['prodid']]))
			{
				$products[$label['prodid']]->setCustomLabel(array(
					'nl' => $label['tekstnl'],
					'fr' => $label['tekstfr']
				));
			}
		}
	}

	public function suggest($term, array $excludeIds = array())
	{
		$query = $this->connection->table('prod');
		$products = $query->where('ArtName_nl', 'like', '%' . $term . '%')
			->where(function($query) use ($excludeIds){
				if(!empty($excludeIds))
					$query->whereNotIn('ID', $excludeIds);
			})
			->take(10)
			->orderBy('ArtName_nl')
			->get(array('ArtName_nl as name', 'ID as prodid'));
		return $products;
	}

	/**
	 * Instantiate products with their basic information
	 */
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

	/**
	 * Find all valid groeperingen for this handelaar and save the id-map in groeperingen property
	 */
	protected function groeperingen()
	{
		$query = $this->connection->table('handelaars_groepering');
		$groeperingen = $query->where('handelaarid', $this->handelaarid)
			->get(array('groeperingid'));
		$this->groeperingen = array_map(function($item)
		{
			return $item['groeperingid'];
		}, $groeperingen);
	}
}