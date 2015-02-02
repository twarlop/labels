<?php
namespace ProductLabels\Setup;
use Framework\Database\Eloquent\Model;

/**
* Queue
*/
class Queue extends Model
{

	protected $table = 'handelaars_labels_queue';
	public $timestamps = false;

	protected $fillable = array('handelaar_id', 'product_id');
	
}