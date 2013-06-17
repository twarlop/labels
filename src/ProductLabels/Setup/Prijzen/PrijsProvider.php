<?php

namespace ProductLabels\Setup\Prijzen;
use ProductLabels\Contract\ProviderInterface;
use DateTime;

/**
* PrijsProvider
*/
class PrijsProvider implements ProviderInterface
{

	protected $handelaarid;
	protected $connection;
	protected $groeperingen;

	public function __construct($handelaarid, $connection, $groeperingen)
	{
		$this->handelaarid = $handelaarid;
		$this->connection = $connection;
		$this->groeperingen = $groeperingen;
	}

	/**
	 * Prices can't be selected by a date that they should be active, 
	 * since a product can still only have one price at a time for an owner.
	 * This was added however to allow for future implementation,
	 * if the implementation fits the current database structure.
	 */
	public function find(array $prodids, array $products, DateTime $datum)
	{
		$prijzenHand = $this->prijzenHand($prodids, $datum);
		$prijzenGroep = $this->prijzenGroep($prodids, $datum);
		$prijzenFab = $this->prijzenFab($prodids, $datum);
		foreach($prijzenHand as $prijs)
		{
			if(isset($products[$prijs->prodid]))
			{
				$products[$prijs->prodid]->setPrijs($prijs);
			}
		}
		foreach($prijzenGroep as $prijs)
		{
			if(isset($products[$prijs->prodid]))
			{
				$products[$prijs->prodid]->setPrijs($prijs);
			}
		}

		foreach($prijzenFab as $prijs)
		{
			if(isset($products[$prijs->prodid]))
			{
				$products[$prijs->prodid]->setPrijs($prijs);
			}
		}
	}

	protected function prijzenHand(array $prodids, DateTime $datum)
	{
		$query = $this->connection->table('prod_prijzen');
		$prijzen = $query->whereType('hand')
			->whereOwner($this->handelaarid)
			->whereIn('prodid', $prodids)
			->get();
		$prijzen = $this->instantiate($prijzen);
		return $prijzen;
	}

	protected function prijzenGroep(array $prodids, DateTime $datum)
	{
		if(count($this->groeperingen))
		{
			$query = $this->connection->table('prod_prijzen');
			$prijzen = $query->whereIn('prodid', $prodids)
				->whereType('groep')
				->whereIn('owner', $this->groeperingen);
			$prijzen = $this->instantiate($prijzen);
			return $prijzen;
		}
		return array();
	}

	protected function prijzenFab(array $prodids, DateTime $datum)
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
		$prijzen = $this->instantiate($prijzen);
		return $prijzen;
	}

	protected function instantiate(array $prijzen)
	{
		$objects = array();
		foreach($prijzen as $prijs)
		{
			array_push($objects, new Prijs($prijs));
		}
		return $objects;
	}
	
}