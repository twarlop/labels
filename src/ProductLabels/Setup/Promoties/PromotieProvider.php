<?php

namespace ProductLabels\Setup\Promoties;
use ProductLabels\Contract\ProviderInterface;
use DateTime;


/**
* PromotieProvider
*/
class PromotieProvider implements ProviderInterface
{
	
	protected $handelaarid;
	protected $connection;
	protected $groeperingen;

	public function __construct($handelaarid, $connection, $groeperingen)
	{
		$this->handelaarid = $handelaarid;
		$this->groeperingen = $groeperingen;
		$this->connection = $connection;
	}


	/**
	 * [findPromoties description]
	 * Find the promoties for certain products active on a certain date.
	 * Make sure to only use the promotion from the correct instance.
	 */
	public function find(array $prodids, array $products, $datum = null)
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
			if(isset($products[$promo->prodid]))
			{
				$products[$promo->prodid]->setPromotie($promo);
			}
		}
		foreach($promosgroep as $promo)
		{
			if(isset($products[$promo->prodid]))
			{
				$products[$promo->prodid]->setPromotie($promo);
			}
		}
		foreach($promoshand as $promo)
		{
			if(isset($products[$promo->prodid]))
			{
				$products[$promo->prodid]->setPromotie($promo);
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
		$promoties = $this->instantiate($promoties);
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
			$promoties = $this->instantiate($promoties);
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
		$promoties = $this->instantiate($promoties);
		return $promoties;
	}

	protected function instantiate($promoties)
	{
		$objects = array();
		foreach($promoties as $promo)
		{
			array_push($objects, new Promotie($promo));
		}
		return $objects;
	}
}