<?php
namespace ProductLabels\Categories;

use ProductLabels\Contract\ProviderInterface;

/**
* CategoryProvider
*/
class CategoryProvider implements ProviderInterface
{
	protected $handelaar_id;

	public function __construct($handelaarid)
	{
		$this->handelaar_id = $handelaarid;
	}

	public function suggest($term)
	{
		$categories = Category::where('Title_short_nl', 'like', '%'. $term . '%')
		->where('ParentID','<>', '0')
		->orderBy('Title_short_nl')
		->take(10)
		->get(array('Title_short_nl as label', 'ID as value'));
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

	public function getInfoType($categoryId)
	{
		$record = CategoryType::where('owner_id', $this->handelaar_id)
			->where('category_id', $categoryId)
			->first();
		return $record;
	}

}