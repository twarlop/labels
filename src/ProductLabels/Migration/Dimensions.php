<?php
namespace ProductLabels\Migration;

use ProductLabels\Contract\MigrationInterface;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;
use ProductLabels\Dimension\Dimension;
use ProductLabels\Dimension\DimensionType;
use ProductLabels\Label\Layout;
use ProductLabels\Label\LayoutDimensions;

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
		'promotion' => 'movePromotion',
		'logoHandelaar' => 'moveLogoHandelaar',
		'logoMerk' => 'moveLogoMerk',
		'text' => 'moveText'
	);

	protected $links = array();

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
		$this->builder->dropIfExists('label_layout_dimensions');
		$this->builder->dropIfExists('label_layout');
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
		$this->builder->create('label_layout', function($t){
			$t->engine = 'InnoDB';
			$t->increments('id');
			$t->boolean('landscape');
			$t->boolean('is_single_label');
			$t->decimal('width');
			$t->decimal('height');
			$t->decimal('widthLabel');
			$t->decimal('heightLabel');
			$t->integer('rows');
			$t->integer('columns');

			$t->timestamps();
		});
		$this->builder->create('label_layout_dimensions', function($t){
			$t->engine = 'InnoDB';
			$t->increments('id');
			$t->integer('layout_id')->unsigned();
			$t->foreign('layout_id')->references('id')->on('label_layout');
			$t->integer('dimension_id')->unsigned();
			$t->foreign('dimension_id')->references('id')->on('label_dimensions');
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
		$links = array();
		foreach($this->callbacks as $k => $v)
		{
			if(isset($this->checks[$k]))
			{
				if($oldLabel[$k] != $this->checks[$k])
				{
					break;
				}
			}
			array_push($links, call_user_func(array($this, $this->callbacks[$k]), $oldLabel));
		}
		array_push($this->links, $links);
		$this->moveLayout($links, $oldLabel);
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
		return $dimension;
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
		return $dimension;
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
		return $dimension;
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
		return $dimension;
	}

	protected function movePromotionText($oldLabel)
	{

	}

	protected function moveLogoHandelaar($oldLabel)
	{
		$dimension = Dimension::create(array(
			'type_id' => DimensionType::LOGO_HANDELAAR,
			'left' => $oldLabel['xHandelaar'],
			'top' => $oldLabel['yHandelaar'],
			'width'=> $oldLabel['wHandelaar'],
			'height' => $oldLabel['hHandelaar']
		));
		return $dimension;
	}

	protected function moveLogoMerk($oldLabel)
	{
		$dimension = Dimension::create(array(
			'type_id' => DimensionType::LOGO_MERK,
			'left' => $oldLabel['xMerk'],
			'top' => $oldLabel['yMerk'],
			'width'=> $oldLabel['wMerk'],
			'height' => $oldLabel['hMerk']
		));
		return $dimension;
	}

	protected function moveText($oldLabel)
	{
		$dimension = Dimension::create(array(
			'type_id' => DimensionType::TEXT,
			'left' => $oldLabel['xBoxTekst'],
			'top' => $oldLabel['yBoxTekst'],
			'width' => $oldLabel['wBoxTekst'],
			'height' => $oldLabel['hLineBoxTekst'] * $oldLabel['linesBoxTekst'],
			'font_size' => $oldLabel['fBoxTekst']
		));
		return $dimension;
	}

	protected function moveLayout($links, $oldLabel)
	{
		$layout = Layout::create(array(
			'landscape' => $oldLabel['landscape'],
			'rows' => $oldLabel['rows'],
			'columns' => $oldLabel['columns'],
			'is_single_label' => $oldLabel['type'] === '1' ? true : false,
			'width' => $oldLabel['widthPDF'],
			'height' => $oldLabel['heightPDF'],
			'widthLabel' => $oldLabel['wEtiket'],
			'heightLabel' => $oldLabel['hEtiket']
		));
		foreach($links as $link)
		{
			$layout->dimensions()->attach($link->id);
		}
	}
	
}