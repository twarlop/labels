<?php

namespace ProductLabels\Migration\Labels;

use ProductLabels\Contract\LabelMigrationInterface;
use ProductLabels\Dimension\Dimension;
use ProductLabels\Dimension\DimensionType;
use ProductLabels\Label\Layout;



/**
* Label1
*/
class Label01 implements LabelMigrationInterface
{

	public function layout()
	{
		$layout = Layout::create(array(
			'name' => '105x42 (A4) x 14',
			'landscape' => '0',
			'is_single_label' => false,
			'width' => '210',
			'height' => '297',
			'widthLabel' => '106',
			'heightLabel' => '42',
			'rows' => '7',
			'columns' => '2'
		));
		return $layout;
	}

	public function photo()
	{
		$dimension = Dimension::create(array(
			'type_id' => DimensionType::PHOTO,
			'left' => '1',
			'top' => '1',
			'height' => '20',
			'width' => '30'
		));
		return $dimension->id;
	}

	public function title()
	{
		$dimension = Dimension::create(array(
			'type_id' => DimensionType::TITLE,
			'left' => '35',
			'top' => '1',
			'height' => '5',
			'width' => '68',
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
			'left' => '35',
			'top' => '10',
			'height' => '36',
			'width' => '68',
			'font_size' => '8',
			'bold' => '0',
			'max_lines' => '9',
			'fill' => '255,255,255',
			'color' => '0,0,0'
		));
		return $dimension->id;
	}

	public function promotion()
	{
		$dimension = Dimension::create(array(
			'type_id' => DimensionType::PROMOTION,
			'left' => '5',
			'top' => '29',
			'height' => '6',
			'width' => '25',
			'font_size' => '16',
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
			'left' => '35',
			'top' => '6',
			'height' => '4',
			'width' => '68',
			'font_size' => '8',
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
			'left' => '5',
			'top' => '37',
			'height' => '4',
			'width' => '68',
			'font_size' => '10',
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
			'left' => '1',
			'top' => '21',
			'height' => '6',
			'width' => '30',
			'font_size' => '16',
			'bold' => '1',
			'fill' => '204,204,204',
			'color' => '0,0,0'
		));
		return $dimension->id;
	}

	public function logoHandelaar()
	{
	}

	public function logoMerk()
	{
	}

	
}