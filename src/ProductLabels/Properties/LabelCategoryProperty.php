<?php

namespace ProductLabels\Properties;
use Framework\Database\Eloquent\Model;

/**
* LabelCategoryProperty
*/
class LabelCategoryProperty extends Model
{
	protected $table = 'label_category_properties';

	protected $fillable = array('property_id', 'category_id', 'owner_id', 'weight');

	public $timestamps = false;

	public function properties()
	{
		return $this->belongsTo('ProductLabels\Properties\Property', 'property_id');
	}
	
}