<?php
namespace ProductLabels\Migration;

use ProductLabels\Setup\Queue as NewQueue;
use DBRM;

/**
* Queue
*/
class Queue extends Base
{

	public function run()
	{
		$this->dropTables();
		$this->createTables();
		$old = $this->oldQueue();
		foreach($old as $record)
		{
			NewQueue::create(array(
				'handelaar_id' => $record['handelaarid'],
				'product_id' => $record['prodid']
			));
		}
	}

	protected function oldQueue()
	{
		$labels = DBRM::connection()->table('labelprintqueue')->get();
		return $labels;
	}

	protected function dropTables()
	{
		$this->builder->dropIfExists('handelaars_labels_queue');
	}

	protected function createTables()
	{
		$this->builder->create('handelaars_labels_queue', function($t){
			$t->engine = 'InnoDB';
			$t->integer('handelaar_id');
			$t->integer('product_id');
		});
	}
}