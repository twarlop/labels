<?php
namespace ProductLabels\Dimension;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
* DimensionType
*/
class DimensionType extends Eloquent
{

	public $timestamps = false;

	protected $table = 'label_dimension_types';

	protected $fillable = array('type');

	const PHOTO = 1;
	const TITLE = 2;
	const PRICE = 3;
	const PROMOTION = 4;
	const PROMOTION_TEXT = 5;
	const TEXT = 6;
	const LOGO_HANDELAAR = 7;
	const LOGO_MERK = 8;

}