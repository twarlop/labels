<?php
namespace ProductLabels\Migration;

use ProductLabels\Contract\MigrationInterface;
use ProductLabels\Setup\Queue as NewQueue;
use ProductLabels\DB;

/**
* Queue
*/
class Queue extends Base implements MigrationInterface
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
		$labels = DB::connection('sos')->table('labelprintqueue')->get();
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