<?php
namespace ProductLabels\Properties;

use ProductLabels\Contract\ProviderInterface;
use Exception;

/**
* PropertyProvider
*/
class PropertyProvider implements ProviderInterface
{

	protected $handelaar_id;

	public function __construct($handelaarid)
	{
		$this->handelaar_id = $handelaarid;
	}

	public function fetchStandardPropertyOrder($category_id)
	{
		if(!is_numeric($category_id))
		{
			throw new Exception('need valid category identifier');
		}
		$results = LabelCategoryProperty::with(array('properties'))
			->where('category_id', $category_id)
			->where('owner_id', 0)
			->orderBy('weight')
			->get();

		return $results->map(function($item){
			return $item->properties;
		});
	}

	public function fetchCustomPropertyOrder($category_id)
	{
		if(!is_numeric($category_id))
		{
			throw new Exception('need valid category identifier');
		}
		$results = LabelCategoryProperty::with(array('properties'))
			->where('category_id', $category_id)
			->where('owner_id', $this->handelaar_id)
			->orderBy('weight')
			->get();

		return $results->map(function($item){
			return $item->properties;
		});
	}
	
}