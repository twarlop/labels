<?php
namespace ProductLabels\Dimension;
use Framework\Database\Eloquent\Model;

/**
* DimensionType
*/
class DimensionType extends Model
{

	public $timestamps = false;

	protected $table = 'label_dimension_types';

	protected $fillable = array('type');

	const PHOTO = 1;
	const TITLE = 2;
	const PRICE = 3;
	const PROMOTION = 4;
	const PROMOTION_TEXT = 5;
	const PROMOTION_STOP = 6;
	const TEXT = 7;
	const LOGO_HANDELAAR = 8;
	const LOGO_MERK = 9;

}