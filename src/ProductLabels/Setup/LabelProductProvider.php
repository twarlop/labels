<?php

namespace ProductLabels\Setup;

use ProductLabels\Contract\ProviderInterface;
use ProductLabels\Setup\Prijzen\PrijsProvider;
use ProductLabels\Setup\Promoties\PromotieProvider;
use DateTime, DBRM;

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
	protected $categoryProvider;

	public function __construct($handelaarid, $propertyProvider, $labelProvider, $categoryProvider)
	{
		$this->handelaarid = $handelaarid;
		$this->connection = DBRM::connection();
		$this->prijsProvider = new PrijsProvider($this->handelaarid, $this->connection, $this->groeperingen);
		$this->promotieProvider = new PromotieProvider($this->handelaarid, $this->connection, $this->groeperingen, $labelProvider);
		$this->propertyProvider = $propertyProvider;
		$this->labelProvider = $labelProvider;
		$this->categoryProvider = $categoryProvider;
	}

	public function find(array $prodids, DateTime $datum)
	{
		if(count($prodids) === 0)
			return array();
		$products = $this->findBases($prodids);
		$products = $this->instantiate($products);
		$this->groeperingen = $this->groeperingen();
		$this->prijsProvider->find($prodids, $products, $datum);
		$this->promotieProvider->find($prodids, $products, $datum);
		$this->propertyProvider->propertiesForProducts($prodids, $products);
		$this->findCustomLabels($prodids, $products);
		$this->categoryProvider->loadInfoTypes($products);
		return $products;
	}

	public function findById($prodid, DateTime $datum)
	{
		$products = $this->find(array($prodid), $datum);
		return $products[$prodid];
	}

	protected function findBases(array $prodids)
	{
        $query = $this->connection->table('prod');
        $products = $query->join('merken', 'merken.merkid', '=', 'prod.Brand')
            ->join('fabrikanten', 'merken.merkid', '=', 'fabrikanten.merkid')
            ->leftJoin('categories', 'categories.ID', '=', 'prod.primairecatid')
            ->whereIn('prod.ID', $prodids)
            ->get(array(
                'prod.ID as product_id',
                'ArtName_nl as title',
                'merken.merk_nl as merknaam',
                'merken.merkid',
                'prod.Image_small as photo',
                'fabrikanten.logo_big as logoMerk',
                'prod.primairecatid as category_id',
                'categories.Title_short_nl as category_nl',
                'categories.Title_short_fr as category_fr',
                'ez_content', 'ez_content_fr',
                'kortnl', 'kortfr'
            ));
        return $products;
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
				$products[$label->prodid]->setCustomLabel(array(
					'nl' => $label->tekstnl,
					'fr' => $label->tekstfr
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
			$product = $this->mergeText($product);
            $product->title = $product->merknaam . ': ' . $product->title;
			$product = new LabelProduct($product);
			$results[$product->product_id] = $product;
		}
		return $results;
	}

	protected function mergeText($product)
	{
		$nl = !empty($product->ez_content) ? $product->ez_content : $product->kortnl;
		$fr = !empty($product->ez_content_fr) ? $product->ez_content_fr : $product->kortfr;
		unset($product->ez_content, $product->ez_content_fr, $product->kortnl, $product->kortfr);
		$product->text = compact(array('nl', 'fr'));
		return $product;
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
			return $item->groeperingid;
		}, $groeperingen);
	}

	public function loadBaseText($productid, $type)
	{
		switch($type)
		{
			//elektrozine
			case 1:
			$fields = array('ez_content as tekstnl', 'ez_content_fr as tekstfr');
			break;
			//shoponsite
			case 3:
			$fields = array('kortnl as tekstnl', 'kortfr as tekstfr');
			break;
		}
		$query = $this->connection->table('prod');
		$product = $query->where('ID', $productid)
			->first($fields);
		return $product;
	}

	public function customiseText($prodid, $nl, $fr)
	{
		$query = $this->connection->table('handelaars_labels');
		$label = $query->where('handelaarid', $this->handelaarid)
			->where('prodid', $prodid)
			->first();
		//ready query object
		$query = $this->connection->table('handelaars_labels');
		if($label)
		{
			//needs deletion?
			if($nl === '' && $fr === '')
			{
				echo '1';
				$query->where('handelaarid', $this->handelaarid)
					->where('prodid', $prodid)
					->delete();
			}
			//else update it
			else
			{
				$query->where('handelaarid', $this->handelaarid)
					->where('prodid', $prodid)
					->update(array(
						'tekstnl' => $nl,
						'tekstfr' => $fr
					));
			}
		}
		else{
			//needs insertion?
			if(!($nl === '' && $fr === ''))
			{
				echo '3';
				$query->insert(array(
					'handelaarid' => $this->handelaarid,
					'prodid' => $prodid,
					'tekstnl' => $nl,
					'tekstfr' => $fr
				));
			}
			//else do nothing
		}
	}
}