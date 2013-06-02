<?php
namespace ProductLabels\Label;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
* LayoutDimension
*/
class LayoutDimension extends Eloquent
{
	protected $table = 'label_layout_dimensions';

	public $timestamps = false;
}