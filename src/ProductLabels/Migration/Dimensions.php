<?php
namespace ProductLabels\Migration;

use ProductLabels\Contract\MigrationInterface;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;
use ProductLabels\Dimension\Dimension;
use ProductLabels\Dimension\DimensionType;

/**
* Dimensions
*
* Dimension migrations will rebuild the current sos_labels table to a newer version
* which will split dimensions into more sustainable records.
*/
class Dimensions extends Base implements MigrationInterface
{

	protected $checks = array(
		'hasFoto' => '1'
	);

	protected $callbacks = array(
		'hasFoto' => 'movePhoto',
		'titel' => 'moveTitle',
		'prijs' => 'movePrijs',
		'promotion' => 'movePromotion'
	);

	public function run()
	{
		$this->dropTables();
		$this->createTables();
		$this->createTypes();
		$oldLabels = $this->getOldLabels();
		foreach($oldLabels as $v)
		{
			$this->moveDimension($v);
		}

	}

	protected function dropTables()
	{
		$this->builder->dropIfExists('label_dimensions');
		$this->builder->dropIfExists('label_dimension_types');
	}

	protected function createTables()
	{
		$this->builder->create('label_dimension_types', function($t){
			$t->engine = 'InnoDB';
			$t->increments('id');
			$t->string('type');
		});
		$this->builder->create('label_dimensions', function($t)
		{
			$t->engine = 'InnoDB';
			$t->increments('id');
			$t->integer('type_id')->unsigned();
			$t->foreign('type_id')->references('id')->on('label_dimension_types');
			$t->decimal('left');
			$t->decimal('top');
			$t->decimal('height');
			$t->decimal('width');	

			//optional columns which define the type of dimension
		
			/**
			 * we don't need anything at the moment for photos
			 */
			
			/**
			 * Text
			 */
			$t->integer('font_size')->nullable();
			$t->integer('fill')->nullabel();
			$t->string('color', '7')->nullable();

			$t->timestamps();
		});
	}

	protected function createTypes()
	{
		$types = array('photo','title','price','promotion', 'promotionText', 'text', 'logoHandelaar', 'logoMerk');
		foreach($types as $value)
		{
			DimensionType::create(array(
				'type' => $value
			));
		}
	}

	protected function getOldLabels()
	{
		$labels = $this->connection->table('sos_labels')->get();
		return $labels;
	}

	protected function moveDimension($oldLabel)
	{
		foreach($this->callbacks as $k => $v)
		{
			if(isset($this->checks[$k]))
			{
				if($oldLabel[$k] != $this->checks[$k])
				{
					break;
				}
			}
			call_user_func(array($this, $this->callbacks[$k]), $oldLabel);
		}
	}

	protected function movePhoto($oldLabel)
	{
		$dimension = Dimension::create(array(
			'type_id' => DimensionType::PHOTO,
			'left' => $oldLabel['xFoto'],
			'top' => $oldLabel['yFoto'],
			'width'=> $oldLabel['wFoto'],
			'height' => $oldLabel['hFoto']
		));
	}

	protected function movePrijs($oldLabel)
	{
		$dimension = Dimension::create(array(
			'type_id' => DimensionType::PRICE,
			'left' => $oldLabel['xPrijs'],
			'top' => $oldLabel['yPrijs'],
			'width'=> $oldLabel['wPrijs'],
			'height' => $oldLabel['hPrijs'],
			'font_size' => $oldLabel['fPrijs']
		));
	}

	protected function moveTitle($oldLabel)
	{
		$dimension = Dimension::create(array(
			'type_id' => DimensionType::TITLE,
			'left' => $oldLabel['xTitel'],
			'top' => $oldLabel['yTitel'],
			'width'=> $oldLabel['wTitel'],
			'height' => $oldLabel['hTitel'],
			'font_size' => $oldLabel['fTitel']
		));
	}

	protected function movePromotion($oldLabel)
	{
		$dimension = Dimension::create(array(
			'type_id' => DimensionType::PROMOTION,
			'left' => $oldLabel['xPrijs'] + 10,
			'top' => $oldLabel['yPrijs'] + 10,
			'width'=> $oldLabel['wPrijs'],
			'height' => $oldLabel['hPrijs'],
			'font_size' => $oldLabel['fPrijs']
		));
	}

	protected function movePromotionText($oldLabel)
	{

	}

	protected function moveLogoHandelaar($oldLabel)
	{

	}

	protected function moveLogoMerk($oldLabel)
	{
		
	}
	
}