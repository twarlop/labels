<?php

namespace ProductLabels\Migration;

/**
* CategoryTypes
*/
class CategoryTypes extends Base
{

	public function run()
	{
		$this->dropTables();
		$this->createTables();
	}

	public function createTables()
	{
		$this->builder->create('handelaars_labels_category_types', function($t)
		{
			$t->engine = 'InnoDB';
			$t->increments('id');
			$t->integer('owner_id');
			$t->integer('category_id');
			$t->string('type', 10);
			$t->timestamps();
		});
	}

	public function dropTables()
	{
		$this->builder->dropIfExists('handelaars_labels_category_types');
	}

}