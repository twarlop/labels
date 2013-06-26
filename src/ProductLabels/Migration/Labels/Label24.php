<?php

namespace ProductLabels\Migration\Labels;

use ProductLabels\Contract\LabelMigrationInterface;
use ProductLabels\Dimension\Dimension;
use ProductLabels\Dimension\DimensionType;
use ProductLabels\Label\Layout;


/**
* Label24
*/
class Label24 implements LabelMigrationInterface
{

	public function layout()
	{
		$layout = Layout::create(array(
			'name' => '148x105 (A4) x4',
			'landscape' => '1',
			'is_single_label' => false,
			'width' => '210',
			'height' => '297',
			'widthLabel' => '148',
			'heightLabel' => '105',
			'rows' => '2',
			'columns' => '2'
		));
		return $layout;
	}

	public function photo()
	{
	}

	public function title()
	{
		$dimension = Dimension::create(array(
			'type_id' => DimensionType::TITLE,
			'left' => '5',
			'top' => '32',
			'height' => '8',
			'width' => '138',
			'font_size' => '12',
			'bold' => '1',
			'fill' => '0,0,0',
			'color' => '255,255,255'
		));
		return $dimension->id;
	}

	public function text()
	{
		$dimension = Dimension::create(array(
			'type_id' => DimensionType::TEXT,
			'left' => '10',
			'top' => '45',
			'height' => '52',
			'width' => '133',
			'font_size' => '11',
			'bold' => '0',
			'max_lines' => '11',
			'fill' => '255,255,255',
			'color' => '0,0,0'
		));
		return $dimension->id;
	}

	public function promotion()
	{
		$dimension = Dimension::create(array(
			'type_id' => DimensionType::PROMOTION,
			'left' => '80',
			'top' => '15',
			'height' => '5',
			'width' => '70',
			'font_size' => '24',
			'bold' => '1',
			'fill' => '255,255,255',
			'color' => '255,0,0'
		));
		return $dimension->id;
	}

	public function promotionText()
	{
		$dimension = Dimension::create(array(
			'type_id' => DimensionType::PROMOTION_TEXT,
			'left' => '5',
			'top' => '40',
			'height' => '5',
			'width' => '133',
			'font_size' => '10',
			'bold' => '1',
			'fill' => '255,255,255',
			'color' => '255,0,0'
		));
		return $dimension->id;
	}

	public function promotionStop()
	{
		$dimension = Dimension::create(array(
			'type_id' => DimensionType::PROMOTION_STOP,
			'left' => '75',
			'top' => '26',
			'height' => '6',
			'width' => '70',
			'font_size' => '12',
			'bold' => '1',
			'fill' => '255,255,255',
			'color' => '255,0,0'
		));
		return $dimension->id;
	}

	public function price()
	{
		$dimension = Dimension::create(array(
			'type_id' => DimensionType::PRICE,
			'left' => '75',
			'top' => '7',
			'height' => '12',
			'width' => '70',
			'font_size' => '24',
			'bold' => '1',
			'fill' => '204,204,204',
			'color' => '0,0,0'
		));
		return $dimension->id;
	}

	public function logoHandelaar()
	{
		// $dimension = Dimension::create(array(
		// 	'type_id' => DimensionType::LOGO_HANDELAAR,
		// 	'left' => '75',
		// 	'top' => '84',
		// 	'height' => '15',
		// 	'width' => '70'
		// ));
		// return $dimension->id;
	}

	public function logoMerk()
	{
		$dimension = Dimension::create(array(
			'type_id' => DimensionType::LOGO_MERK,
			'left' => '1',
			'top' => '1',
			'height' => '30',
			'width' => '70'
		));
		return $dimension->id;
	}

	
}