<?php

namespace ProductLabels\Setup;

use ProductLabels\Contract\ProviderInterface;
use ProductLabels\DB;
use DateTime;

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
		$products = $this->instantiate($products);
		$this->groeperingen = $this->groeperingen();
		$this->findPrijzen($prodids, $products);
		$this->findPromoties($prodids, $products);
		$this->findEigenschappen($prodids, $products);
		return $products;
	}

	protected function findPrijzen(array $prodids, array $products, $datum = null)
	{
		$prijzenHand = $this->prijzenHand($prodids);
		$prijzenGroep = $this->prijzenGroep($prodids);
		$prijzenFab = $this->prijzenFab($prodids);
		foreach($prijzenHand as $prijs)
		{
			if(isset($products[$prijs['prodid']]))
			{
				$products[$prijs['prodid']]->setPrijs($prijs);
			}
		}
		foreach($prijzenGroep as $prijs)
		{
			if(isset($products[$prijs['prodid']]))
			{
				$products[$prijs['prodid']]->setPrijs($prijs);
			}
		}

		foreach($prijzenFab as $prijs)
		{
			if(isset($products[$prijs['prodid']]))
			{
				$products[$prijs['prodid']]->setPrijs($prijs);
			}
		}
	}

	protected function prijzenHand(array $prodids)
	{
		$query = $this->connection->table('prod_prijzen');
		$prijzen = $query->whereType('hand')
			->whereOwner($this->handelaarid)
			->whereIn('prodid', $prodids)
			->get();
		return $prijzen;
	}

	protected function prijzenGroep(array $prodids)
	{
		if(count($this->groeperingen))
		{
			$query = $this->connection->table('prod_prijzen');
			$prijzen = $query->whereIn('prodid', $prodids)
				->whereType('groep')
				->whereIn('owner', $this->groeperingen);
			return $prijzen;
		}
		return array();
	}

	protected function prijzenFab(array $prodids)
	{
		$query = $this->connection->table('prod_prijzen');
		$query->join('prod', function($query)
			{
				$query->on('prod.ID', '=', 'prod_prijzen.prodid')
					->on('prod.Brand', '=', 'prod_prijzen.owner');
			});
		$prijzen = $query->where('prod_prijzen.type', 'fab')
			->whereIn('prodid', $prodids)
			->get(array('prod_prijzen.*'));
		return $prijzen;
	}

	/**
	 * [findPromoties description]
	 * Find the promoties for certain products active on a certain date.
	 * Make sure to only use the promotion from the correct instance.
	 */
	protected function findPromoties(array $prodids, array $products, $datum = null)
	{
		if(empty($datum))
		{
			$datum = new DateTime();
		}
		$promoshand = $this->promosHand($prodids, $datum);
		$promosgroep = $this->promosGroep($prodids, $datum);
		$promosfab = $this->promosFab($prodids, $datum);
		foreach($promoshand as $promo)
		{
			if(isset($products[$promo['prodid']]))
			{
				$products[$promo['prodid']]->setPromotie($promo);
			}
		}
		foreach($promosgroep as $promo)
		{
			if(isset($products[$promo['prodid']]))
			{
				$products[$promo['prodid']]->setPromotie($promo);
			}
		}
		foreach($promoshand as $promo)
		{
			if(isset($products[$promo['prodid']]))
			{
				$products[$promo['prodid']]->setPromotie($promo);
			}
		}
	}

	protected function promosFab(array $prodids, DateTime $datum)
	{
		$query = $this->connection->table('prod_promo');
		$query->join('prod', function($query){
			$query->on('prod.ID', '=', 'prod_promo.prodid')
				->on('prod.Brand', '=', 'prod_promo.owner');
		});
		$promoties = $query->where('type', 'fab')
			->whereIn('prodid', $prodids)
			->where('start', '<=', $datum)
			->where('stop', '>=', $datum)
			->get();
		return $promoties;
	}

	protected function promosGroep(array $prodids, DateTime $datum)
	{
		if(count($this->groeperingen))
		{
			$query = $this->connection->table('prod_promo');
			$promoties = $query->whereIn('prodid', $prodids)
				->whereType('groep')
				->whereIn('owner', $this->groeperingen)
				->where('start', '<=', $datum)
				->where('stop', '>=', $datum)
				->get();
			return $promoties;
		}
		return array();
	}

	protected function promosHand(array $prodids, DateTime $datum)
	{
		$query = $this->connection->table('prod_promo');
		$promoties = $query->whereIn('prodid', $prodids)
			->whereType('hand')
			->whereOwner($this->handelaarid)
			->where('start', '<=', $datum)
			->where('stop', '>=', $datum)
			->get();
		return $promoties;
	}
	/**
	 * Finds all the properties for a certain product.
	 * Do not forget to remap them so we can make sure only properties which have been selected by the handelaar will be shown on the labels
	 */
	protected function findEigenschappen(array $prodids, array $products)
	{
		//split prodids into prodids that use standard property sort and prodids that use custom property sort
		list($regularIds, $customIds) = $this->splitProdids($prodids);
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
			var_dump($customProdids);
	}

	/**
	 * Finds the custom label text provided by the handelaar
	 */
	protected function findCustomLabels(array $prodids, array $products)
	{

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