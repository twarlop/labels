<?php
namespace ProductLabels\Categories;

use ProductLabels\Contract\ProviderInterface;

/**
* CategoryProvider
*/
class CategoryProvider implements ProviderInterface
{

	public function suggest($term)
	{
		$categories = Category::where('Title_short_nl', 'like', '%'. $term . '%')
		->where('ParentID','<>', '0')
		->orderBy('Title_short_nl')
		->take(10)
		->get(array('Title_short_nl as label', 'ID as value'));
		return $categories;
	}

}