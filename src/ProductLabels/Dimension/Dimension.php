<?php
namespace ProductLabels\Dimension;

use Framework\Database\Eloquent\Model;
use ProductLabels\Contract\DimensionInterface;

/**
* Dimension
*
* This model holds a configuartion for each key part of a product label.
* it has left, top, width, heigth property
*/
class Dimension extends Model implements DimensionInterface
{
	protected $table = 'label_dimensions';

	protected $fillable = array('type_id', 'left','top', 'width', 'height', 'font_size', 'bold', 'max_lines', 'fill', 'color');

	public function type()
	{
		return $this->belongsTo('ProductLabels\Dimension\DimensionType', 'type_id');
	}
}