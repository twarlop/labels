<?php

namespace ProductLabels\Migration\Labels;

use ProductLabels\Contract\LabelMigrationInterface;
use ProductLabels\Dimension\Dimension;
use ProductLabels\Dimension\DimensionType;
use ProductLabels\Label\Layout;


/**
* Label14
*/
class Label14 implements LabelMigrationInterface
{

	public function layout()
	{
		$layout = Layout::create(array(
			'name' => '159x104 (Label)',
			'landscape' => '1',
			'is_single_label' => false,
			'width' => '104',
			'height' => '159',
			'widthLabel' => '159',
			'heightLabel' => '104',
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
			'height' => '58',
			'width' => '58'
		));
		return $dimension->id;
	}

	public function title()
	{
		$dimension = Dimension::create(array(
			'type_id' => DimensionType::TITLE,
			'left' => '60',
			'top' => '1',
			'height' => '8',
			'width' => '98',
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
			'left' => '60',
			'top' => '14',
			'height' => '61',
			'width' => '98',
			'font_size' => '8',
			'bold' => '0',
			'max_lines' => '17',
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
			'top' => '80',
			'height' => '10',
			'width' => '55',
			'font_size' => '30',
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
			'left' => '60',
			'top' => '10',
			'height' => '4',
			'width' => '98',
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
			'left' => '61',
			'top' => '69',
			'height' => '8',
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
			'top' => '70',
			'height' => '10',
			'width' => '55',
			'font_size' => '30',
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
			'left' => '59',
			'top' => '76',
			'height' => '27',
			'width' => '50'
		));
		return $dimension->id;
	}

	public function logoMerk()
	{
		$dimension = Dimension::create(array(
			'type_id' => DimensionType::LOGO_MERK,
			'left' => '112',
			'top' => '76',
			'height' => '27',
			'width' => '45'
		));
		return $dimension->id;
	}

	
}