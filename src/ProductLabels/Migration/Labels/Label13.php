<?php

namespace ProductLabels\Migration\Labels;

use ProductLabels\Contract\LabelMigrationInterface;
use ProductLabels\Dimension\Dimension;
use ProductLabels\Dimension\DimensionType;
use ProductLabels\Label\Layout;


/**
* Label13
*/
class Label13 implements LabelMigrationInterface
{

	public function layout()
	{
		//er is iets mis met dit formaat? naam klopt niet met de afmetingen :-/
		$layout = Layout::create(array(
			'name' => '150x24 (Label)',
			'landscape' => '1',
			'is_single_label' => false,
			'width' => '29',
			'height' => '170',
			'widthLabel' => '170',
			'heightLabel' => '29',
			'rows' => '1',
			'columns' => '1'
		));
		return $layout;
	}

	public function photo()
	{
		$dimension = Dimension::create(array(
			'type_id' => DimensionType::PHOTO,
			'left' => '1',
			'top' => '1',
			'height' => '29',
			'width' => '40'
		));
		return $dimension->id;
	}

	public function title()
	{
		$dimension = Dimension::create(array(
			'type_id' => DimensionType::TITLE,
			'left' => '45',
			'top' => '1',
			'height' => '8',
			'width' => '124',
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
			'left' => '',
			'top' => '',
			'height' => '',
			'width' => '',
			'font_size' => '',
			'bold' => '0',
			'max_lines' => '',
			'fill' => '255,255,255',
			'color' => '0,0,0'
		));
		return $dimension->id;
	}

	public function promotion()
	{
		$dimension = Dimension::create(array(
			'type_id' => DimensionType::PROMOTION,
			'left' => '45',
			'top' => '19',
			'height' => '8',
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
			'left' => '95',
			'top' => '8',
			'height' => '9',
			'width' => '30',
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
			'left' => '45',
			'top' => '12',
			'height' => '7',
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
		$dimension = Dimension::create(array(
			'type_id' => DimensionType::LOGO_HANDELAAR,
			'left' => '94',
			'top' => '16',
			'height' => '12',
			'width' => '75'
		));
		return $dimension->id;
	}

	public function logoMerk()
	{
	}

	
}