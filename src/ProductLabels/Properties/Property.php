<?php
namespace ProductLabels\Properties;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
* Property
*/
class Property extends Eloquent
{

	protected $table = 'cat_invoervelden';
	protected $primaryKey = 'catinvoerveldid';

	public $timestamps = false;
	
}