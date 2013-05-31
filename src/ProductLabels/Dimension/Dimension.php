<?php
namespace ProductLabels\Dimension;

use Illuminate\Database\Eloquent\Model as Eloquent;
use ProductLabels\Contract\DimensionInterface;

/**
* Dimension
*/
class Dimension extends Eloquent implements DimensionInterface
{
	protected $table = "label_dimensions";
}