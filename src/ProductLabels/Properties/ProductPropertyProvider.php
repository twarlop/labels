<?php
namespace ProductLabels\Properties;

use ProductLabels\Contract\ProviderInterface;


/**
* ProductPropertyProvider
*/
class ProductPropertyProvider implements ProviderInterface
{
	protected $connection;

	public function __construct($connection)
	{
		$this->connection = $connection;
	}

	public function findForProducts(array $prodids){
		if(empty($prodids))
			return array();
		$query = $this->connection->table('prod_velden');
		
		$properties = $query->join('prod_velden_inhoud', 'prod_velden.inhoudid', '=', 'prod_velden_inhoud.id')
			->whereIn('prod_velden.prodid', $prodids)
			->get(array('prod_velden_inhoud.*', 'prod_velden.prodid'));

		return $properties;
	}
}