<?php

namespace ProductLabels\Setup\Prijzen;
use ProductLabels\Contract\ProviderInterface;

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

	public function find(array $prodids, array $products, $datum = null)
	{
		$prijzenHand = $this->prijzenHand($prodids);
		$prijzenGroep = $this->prijzenGroep($prodids);
		$prijzenFab = $this->prijzenFab($prodids);
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

	protected function prijzenHand(array $prodids)
	{
		$query = $this->connection->table('prod_prijzen');
		$prijzen = $query->whereType('hand')
			->whereOwner($this->handelaarid)
			->whereIn('prodid', $prodids)
			->get();
		$prijzen = $this->instantiate($prijzen);
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
			$prijzen = $this->instantiate($prijzen);
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