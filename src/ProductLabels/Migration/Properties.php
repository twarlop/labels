<?php
namespace ProductLabels\Migration;
use ProductLabels\Properties\LabelCategoryProperty;

/**
* Properties
*/
class Properties extends Base
{
	
	public function run()
	{
		$this->dropTables();
		$this->createTables();
		$this->createStandard();
	}

	protected function dropTables()
	{
		$this->builder->dropIfExists('label_category_properties');
	}

	protected function createTables()
	{
		$this->builder->create('label_category_properties', function($t){
			$t->engine = 'InnoDB';
			$t->increments('id');
			$t->integer('owner_id');
			$t->integer('category_id');
			$t->integer('property_id');
			$t->integer('weight');
		});
	}

	protected function createStandard()
	{
		//voor elke bestaande categorie een standaard invoeren
		$properties = $this->connection->table('cat_invoervelden')->get(array(
			'catinvoerveldid',
			'catid',
			'volgorde'
		));
		foreach($properties as $property)
		{
			LabelCategoryProperty::create(array(
				'owner_id' => 0,
				'category_id' => $property['catid'],
				'property_id' => $property['catinvoerveldid'],
				'weight' => $property['volgorde']
			));
		}
	}

}