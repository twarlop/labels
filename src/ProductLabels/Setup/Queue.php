<?php
namespace ProductLabels\Setup;

use Illuminate\Database\Eloquent\Model as Eloquent;
/**
* Queue
*/
class Queue extends Eloquent
{

	protected $table = 'handelaars_labels_queue';
	public $timestamps = false;

	protected $fillable = array('handelaar_id', 'product_id');
	
}