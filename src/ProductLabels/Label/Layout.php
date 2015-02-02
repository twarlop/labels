<?php
namespace ProductLabels\Label;
use Framework\Database\Eloquent\Model;

/**
* Layout
*
* Holds a property for each possible dimension that is valid for the labels to be used on the pages
*
* example: this will not hold a photo dimension if the current layout to be printed doens't need a photo
*/
class Layout extends Model
{

	protected $table = 'label_layout';

	protected $fillable = array('id', 'name', 'width', 'height', 'landscape', 'widthLabel', 'heightLabel', 'isA4', 'rows', 'columns', 'is_single_label');

	public function dimensions()
	{
		return $this->belongsToMany('ProductLabels\Dimension\Dimension', 'label_layout_dimensions', 'layout_id', 'dimension_id');
	}

	public function itemsPerPage()
	{
		return $this->rows * $this->columns;
	}

}