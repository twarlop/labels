<?php

namespace ProductLabels\Categories;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
* CategoryType
*/
class CategoryType extends Eloquent
{

	protected $table = 'handelaars_labels_category_types';
	protected $fillable = array('owner_id', 'category_id', 'type');

}