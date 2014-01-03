<?php
namespace ProductLabels\Categories;

use ProductLabels\Contract\ProviderInterface;

/**
* CategoryProvider
*/
class CategoryProvider implements ProviderInterface
{
	protected $handelaar_id;
	protected $infoTypes = array();

	public function __construct($handelaarid)
	{
		$this->handelaar_id = $handelaarid;
	}

	public function suggest($term)
	{
        global $LANG;
        $l = $LANG == 2 ? 'fr' : 'nl';
        $categories = Category::where('Title_short_' . $l, 'like', '%'. $term . '%')
            ->where('ParentID','<>', '0')
            ->where('Active', 1)
            ->orderBy('Title_short_' . $l)
            ->take(10)
            ->get(array('Title_short_' . $l . ' as label', 'ID as value'));
        return $categories;
	}

	public function setInfoType($categoryId, $type)
	{
		if($type === 'properties' || $type === 'text'){
			$record = CategoryType::where('owner_id', $this->handelaar_id)
				->where('category_id', $categoryId)
				->first();
			if($record)
			{
				$record->type = $type;
				$record->save();
			}
			else
			{
				CategoryType::create(array(
					'owner_id' => $this->handelaar_id,
					'category_id' => $categoryId,
					'type' => $type
				));
			}
		}
		else
		{
			CategoryType::where('owner_id', $this->handelaar_id)
				->where('category_id', $categoryId)
				->delete();
		}

	}

	public function findInfoType($categoryId)
	{
		$record = CategoryType::where('owner_id', $this->handelaar_id)
			->where('category_id', $categoryId)
			->first();
		return $record;
	}

	public function loadInfoTypes(array $products = array())
	{
		$categoryIds = $this->categoryIds($products);
		if(!empty($categoryIds))
		{
			$types = CategoryType::where('owner_id', $this->handelaar_id)
				->whereIn('category_id', $categoryIds)
				->get();
			foreach($types as $type)
			{
				$this->infoTypes[$type->category_id] = $type;
			}
		}
	}

	public function getInfoType($categoryId)
	{
		if(isset($this->infoTypes[$categoryId])){
			return $this->infoTypes[$categoryId]->type;
		}
		return;
	}

	protected function categoryIds(array $products = array())
	{
		$categoryIds = array();
		array_walk($products, function($product) use(&$categoryIds){
			array_push($categoryIds, $product->category_id);
		});
		$categoryIds = array_unique($categoryIds);
		return $categoryIds;
	}

}