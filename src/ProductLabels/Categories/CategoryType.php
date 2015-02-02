<?php

namespace ProductLabels\Categories;

use Framework\Database\Eloquent\Model;

/**
* CategoryType
*/
class CategoryType extends Model
{

	protected $table = 'handelaars_labels_category_types';
	protected $fillable = array('owner_id', 'category_id', 'type');

}