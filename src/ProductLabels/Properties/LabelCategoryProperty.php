<?php

namespace ProductLabels\Properties;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
* LabelCategoryProperty
*/
class LabelCategoryProperty extends Eloquent
{
	protected $table = 'label_category_properties';

	protected $fillable = array('property_id', 'category_id', 'owner', 'weight');

	public $timestamps = false;

	public function properties()
	{
		return $this->belongsTo('ProductLabels\Properties\Property', 'property_id');
	}
	
}