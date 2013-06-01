<?php
namespace ProductLabels\Dimension;

use ProductLabels\Contract\DimensionInterface;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
* Dimension
*
* This model holds a configuartion for each key part of a product label.
* it has left, top, width, heigth property
*/
class Dimension extends Eloquent implements DimensionInterface
{
	protected $table = 'label_dimensions';

	protected $fillable = array('type_id', 'left','top', 'width', 'height', 'font_size');
}