<?php

namespace ProductLabels\Migration\Labels;

use ProductLabels\Contract\LabelMigrationInterface;
use ProductLabels\Dimension\Dimension;
use ProductLabels\Dimension\DimensionType;
use ProductLabels\Label\Layout;


/**
* Label8
*/
class Label08 implements LabelMigrationInterface
{

	public function layout()
	{
		$layout = Layout::create(array(
			'name' => '88x36 (Label)',
			'landscape' => '1',
			'is_single_label' => false,
			'width' => '36',
			'height' => '88',
			'widthLabel' => '88',
			'heightLabel' => '36',
			'rows' => '1',
			'columns' => '1'
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
			'left' => '1',
			'top' => '1',
			'height' => '8',
			'width' => '86',
			'font_size' => '12',
			'bold' => '1',
			'fill' => '0,0,0',
			'color' => '255,255,255'
		));
		return $dimension->id;
	}

	public function text()
	{
		
	}

	public function promotion()
	{
		$dimension = Dimension::create(array(
			'type_id' => DimensionType::PROMOTION,
			'left' => '5',
			'top' => '20',
			'height' => '10',
			'width' => '33',
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
			'left' => '',
			'top' => '',
			'height' => '',
			'width' => '',
			'font_size' => '',
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
			'left' => '42',
			'top' => '10',
			'height' => '5',
			'width' => '45',
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
			'top' => '10',
			'height' => '10',
			'width' => '38',
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
		$dimension = Dimension::create(array(
			'type_id' => DimensionType::LOGO_MERK,
			'left' => '42',
			'top' => '17',
			'height' => '18',
			'width' => '45'
		));
		return $dimension->id;
	}

	
}