<?php

namespace ProductLabels\Migration\Labels;

use ProductLabels\Contract\LabelMigrationInterface;
use ProductLabels\Dimension\Dimension;
use ProductLabels\Dimension\DimensionType;
use ProductLabels\Label\Layout;


/**
* Label6
*/
class Label06 implements LabelMigrationInterface
{

	public function layout()
	{
		$layout = Layout::create(array(
			'name' => '199x143 (A4) x2',
			'landscape' => '0',
			'is_single_label' => false,
			'width' => '210',
			'height' => '297',
			'widthLabel' => '200',
			'heightLabel' => '144',
			'rows' => '2',
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
			'height' => '63',
			'width' => '100'
		));
		return $dimension->id;
	}

	public function title()
	{
		$dimension = Dimension::create(array(
			'type_id' => DimensionType::TITLE,
			'left' => '1',
			'top' => '65',
			'height' => '8',
			'width' => '195',
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
			'left' => '1',
			'top' => '79',
			'height' => '45',
			'width' => '195',
			'font_size' => '12',
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
			'left' => '110',
			'top' => '40',
			'height' => '10',
			'width' => '70',
			'font_size' => '36',
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
			'left' => '1',
			'top' => '74',
			'height' => '5',
			'width' => '195',
			'font_size' => '12',
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
			'left' => '110',
			'top' => '55',
			'height' => '5',
			'width' => '85',
			'font_size' => '20',
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
			'left' => '105',
			'top' => '25',
			'height' => '12',
			'width' => '80',
			'font_size' => '36',
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
			'left' => '1',
			'top' => '123',
			'height' => '20',
			'width' => '195'
		));
		return $dimension->id;
	}

	public function logoMerk()
	{
		$dimension = Dimension::create(array(
			'type_id' => DimensionType::LOGO_MERK,
			'left' => '105',
			'top' => '1',
			'height' => '20',
			'width' => '90'
		));
		return $dimension->id;
	}

}