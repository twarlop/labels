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

	public function fetchCustomProperties(array $categoryIds)
	{

	}

	public function fetchStandardProperties(array $categoryIds)
	{
		
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

		$results = $results->map(function($item){
			return $item->properties;
		});
		return $results;
	}

	/**
	 * start from the bottom and begin with 1, next you up them by 1
	 * this will make it easier on sorting when there is room for extra property info an a label.
	 * Unsorted items can then use 0 which makes more sense.
	 */
	public function sync($category, $properties)
	{
		$this->clearCategory($category);
		$weight = 1;
		while($property = array_shift($properties))
		{
			LabelCategoryProperty::create(array(
				'category_id' => $category,
				'property_id' => $property,
				'owner_id' => $this->handelaar_id,
				'weight' => $weight
			));
			$weight++;
		}
	}

	protected function clearCategory($category)
	{
		$links = LabelCategoryProperty::where('owner_id', $this->handelaar_id)
			->where('category_id', $category)
			->get();
		foreach($links as $link)
		{
			$link->delete();
		}
	}
	
}