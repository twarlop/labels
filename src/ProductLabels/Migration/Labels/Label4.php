<?php

namespace ProductLabels\Migration\Labels;

use ProductLabels\Contract\LabelMigrationInterface;
use ProductLabels\Dimension\Dimension;
use ProductLabels\Dimension\DimensionType;
use ProductLabels\Label\Layout;


/**
* Label4
*/
class Label4 implements LabelMigrationInterface
{

	public function layout()
	{
		$layout = Layout::create(array(
			'name' => '105x72 (A4) x8',
			'landscape' => '',
			'is_single_label' => false,
			'width' => '210',
			'height' => '297',
			'widthLabel' => '106',
			'heightLabel' => '74',
			'rows' => '4',
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
			'height' => '30',
			'width' => '40'
		));
		return $dimension->id;
	}

	public function title()
	{
		$dimension = Dimension::create(array(
			'type_id' => DimensionType::TITLE,
			'left' => '42',
			'top' => '1',
			'height' => '5',
			'width' => '61',
			'font_size' => '8',
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
			'left' => '40',
			'top' => '11',
			'height' => '65',
			'width' => '63',
			'font_size' => '8',
			'bold' => '0',
			'max_lines' => '16',
			'fill' => '255,255,255',
			'color' => '0,0,0'
		));
		return $dimension->id;
	}

	public function promotion()
	{
		$dimension = Dimension::create(array(
			'type_id' => DimensionType::PROMOTION,
			'left' => '1',
			'top' => '42',
			'height' => '5',
			'width' => '35',
			'font_size' => '20',
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
			'left' => '42',
			'top' => '6',
			'height' => '5',
			'width' => '61',
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
			'left' => '1',
			'top' => '53',
			'height' => '5',
			'width' => '35',
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
			'left' => '1',
			'top' => '35',
			'height' => '10',
			'width' => '35',
			'font_size' => '20',
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