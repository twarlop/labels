<?php
namespace ProductLabels\Properties;
use Framework\Database\Eloquent\Model;

/**
* Property
*/
class Property extends Model
{

	protected $table = 'cat_invoervelden';
	protected $primaryKey = 'catinvoerveldid';

	public $timestamps = false;
	
}