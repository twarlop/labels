<?php
namespace ProductLabels\Migration;

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
class Dimensions extends Base
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
		// $oldLabels = $this->getOldLabels();
		// foreach($oldLabels as $v)
		// {
		// 	$this->moveDimension($v);
		// }
		$this->migrateDimensions();
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
			
			/**
			 * Text only columns
			 */
			$t->integer('font_size')->nullable();
			$t->boolean('bold');
			$t->integer('max_lines')->nullable();
			$t->string('fill', 11)->nullable();
			$t->string('color', 11)->nullable();

			$t->timestamps();
		});
		$this->builder->create('label_layout', function($t){
			$t->engine = 'InnoDB';
			$t->increments('id');
			$t->string('name', 40);
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
		$types = array('photo','title','price','promotion', 'promotionText', 'promotionStop', 'text', 'logoHandelaar', 'logoMerk');
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
			'font_size' => $oldLabel['fPrijs'],
			'fill' => '204,204,204',
			'color' => '0,0,0'
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
			'font_size' => $oldLabel['fTitel'],
			'fill' => '0,0,0',
			'color' => '255,255,255'
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
			'font_size' => $oldLabel['fPrijs'],
			'color' => '153,0,0',
			'fill' => '204,204,204'
		));
		return $dimension;
	}

	protected function movePromotionText($oldLabel)
	{
		echo 'need to create dimensions for promotionText';
		echo 'dont forget to use the proper collor settings';
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
			'name' => $oldLabel['afmeting'],
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

	protected function migrateDimensions()
	{
		$files = scandir(__DIR__ . '/Labels');
		$exclude = array('.', '..', '.DS_STORE');
		$files = array_diff($files, $exclude);
		foreach($files as $file)
		{
			$file = 'ProductLabels\\Migration\\Labels\\' . substr($file, 0, strlen($file) - 4);
			$migrator = new $file();
			$layout = $migrator->layout();
			$photo = $migrator->photo();
			$text = $migrator->text();
			$price = $migrator->price();
			$promo = $migrator->promotion();
			$stop = $migrator->promotionStop();
			$promotext = $migrator->promotionText();
			$title = $migrator->title();
			$merk = $migrator->logoMerk();
			$handelaar = $migrator->logoHandelaar();
			$dimensions = compact('photo', 'text', 'price', 'promo','stop','promotext','title','merk', 'handelaar');
			$dimensionIds = array();
			array_walk($dimensions, function($item) use (&$dimensionIds){
				if($item)
				{
					array_push($dimensionIds, $item);
				}
			});
			$layout->dimensions()->sync($dimensionIds);
		}
	}
	
}