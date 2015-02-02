<?php
namespace ProductLabels\Properties;

use ProductLabels\Contract\ProviderInterface;
use ProductLabels\Properties\Property;
use ProductLabels\Properties\LabelCategoryProperty;
use Exception;

/**
* PropertyProvider
*/
class PropertyProvider implements ProviderInterface
{

	protected $handelaar_id;

	/**
	 * Map containing the properties that should be loaded for each category
	 */
	protected $categoryMap = array();

	/*
	 * Map containing properties per product, loaded using the category map
	 */
	protected $productMap = array();

	protected $productPropertyProvider;

	public function __construct($handelaarid, $connection)
	{
		$this->connection = $connection;
		$this->productPropertyProvider = new ProductPropertyProvider($connection);
		$this->handelaar_id = $handelaarid;
        $this->cleanNonExisting();
	}

	/**
	 * We will use maps to load the properties per product.
	 * We first build a map for the properties needed per category
	 * Next we build a map with properties per product with property-id as index
	 * At last we can return the correct properties using the category map and the property map
	 */
	public function propertiesForProducts(array $prodids, array $products)
	{
		$categoryIds = $this->getCategoryIds($products);
		$lists = $this->listCustomAndStandard($categoryIds);
		extract($lists);
		$properties = $this->fetchCustomPropertyOrders($custom);
		$this->addToCategoryMap($properties);
		$properties = $this->fetchStandardPropertyOrders($standard);
		$this->addToCategoryMap($properties);
		$properties = $this->productPropertyProvider->findForProducts($prodids);
		$this->setProductProperties($properties, $products);
	}

	protected function setProductProperties(array $properties, array $products)
	{
		foreach($properties as $property)
		{
			if(isset($products[$property->prodid]))
			{
				$products[$property->prodid]->addProperty($property);
			}
		}
	}

	protected function getCategoryIds(array $products)
	{
		return array_map(function($item)
		{
			return $item->category_id;
		}, $products);
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

	public function fetchStandardPropertyOrders(array $categoryIds)
	{
		if(empty($categoryIds))
			return array();
		$results = LabelCategoryProperty::with(array('properties'))
			->whereIn('category_id', $categoryIds)
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

	public function fetchCustomPropertyOrders(array $categoryIds)
	{
		if(empty($categoryIds))
			return array();

		$results = LabelCategoryProperty::with(array('properties'))
			->whereIn('category_id', $categoryIds)
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

	/**
	 * We fetch only the custom lists, so we know which ones don't have a custom sorting
	 */
	protected function listCustomAndStandard(array $categoryIds)
	{
		$custom = array();
		$standard = array();

        if(empty($categoryIds))
        {
            return compact('standard', 'custom');
        }

		$lists = LabelCategoryProperty::whereIn('category_id', $categoryIds)
			->where('owner_id', $this->handelaar_id)
			->distinct()
			->get(array('category_id'));
		foreach($lists as $list)
		{
			array_push($custom, $list->category_id);
		}
		//fill $standard with ids that do not occur in custom
		$standard = array_diff($categoryIds, $custom);
		return compact(array('standard', 'custom'));
	}

	protected function addToCategoryMap($properties)
	{
		foreach($properties as $property)
		{
			if(!isset($this->categoryMap[$property->catid]))
			{
				$this->categoryMap[$property->catid] = array();
			}
			array_push($this->categoryMap[$property->catid], $property);
		}
	}

	protected function getCategoriesFromMap($category_id)
	{
		if(isset($this->categoryMap[$category_id]))
		{
			return $this->categoryMap[$category_id];
		}
		return array();
	}

	/**
	 * Filter properties that do not have a value for the given language
	 */
	public function propertiesFromMap($product, $language)
	{
		$answer = array();
		$properties = $product->properties;
		$map  = $this->getCategoriesFromMap($product->category_id);
		foreach($map as $property)
		{
			if(isset($properties[$property->catinvoerveldid]))
			{
                switch($language)
                {
                    case 'nl':

                        $field = 'inhoud' . $language;
						if(!empty($properties[$property->catinvoerveldid]->$field))
                        {
                            array_push($answer, array(
                                'property' => $property,
                                'value' => $properties[$property->catinvoerveldid]
                            ));
                        }
                        break;
                    case 'fr':
                        $field = 'inhoud' . $language;
						if(!empty($properties[$property->catinvoerveldid]->$field))
                        {
                            array_push($answer, array(
                                'property' => $property,
                                'value' => $properties[$property->catinvoerveldid]
                            ));
                        }
                        else if(in_array($property->data_type, array('num', 'boolean')))
                        {
                            array_push($answer, array(
                                'property' => $property,
                                'value' => $properties[$property->catinvoerveldid]
                            ));
                        }
                        break;
                }
			}
		}
		return $answer;
	}

    protected function cleanNonExisting()
    {
        $propertyids = LabelCategoryProperty::where('owner_id', $this->handelaar_id)
            ->orWhere('owner_id', 0)
            ->lists('property_id');
        if(!empty($propertyids)){
            $existingIds = Property::whereIn('catinvoerveldid', $propertyids)->lists('catinvoerveldid');
            $diff = array_diff($propertyids, $existingIds);
            if(!empty($diff)){
                $selection = LabelCategoryProperty::whereIn('property_id', $diff)->delete();
            }
        }

    }
	
}