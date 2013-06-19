<?php

namespace ProductLabels\Migration\Labels;

use ProductLabels\Contract\LabelMigrationInterface;
use ProductLabels\Dimension\Dimension;
use ProductLabels\Dimension\DimensionType;
use ProductLabels\Label\Layout;


/**
* Label19
*/
class Label19 implements LabelMigrationInterface
{

	public function layout()
	{
		$layout = Layout::create(array(
			'name' => '150x210 (A4) x2',
			'landscape' => '1',
			'is_single_label' => false,
			'width' => '210',
			'height' => '297',
			'widthLabel' => '148',
			'heightLabel' => '210',
			'rows' => '1',
			'columns' => '2'
		));
		return $layout;
	}

	public function photo()
	{
		$dimension = Dimension::create(array(
			'type_id' => DimensionType::PHOTO,
			'left' => '25',
			'top' => '25',
			'height' => '50',
			'width' => '50'
		));
		return $dimension->id;
	}

	public function title()
	{
		$dimension = Dimension::create(array(
			'type_id' => DimensionType::TITLE,
			'left' => '1',
			'top' => '1',
			'height' => '8',
			'width' => '146',
			'font_size' => '18',
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
			'left' => '4',
			'top' => '95',
			'height' => '75',
			'width' => '140',
			'font_size' => '12',
			'bold' => '0',
			'max_lines' => '13',
			'fill' => '255,255,255',
			'color' => '0,0,0'
		));
		return $dimension->id;
	}

	public function promotion()
	{
		$dimension = Dimension::create(array(
			'type_id' => DimensionType::PROMOTION,
			'left' => '85',
			'top' => '52',
			'height' => '10',
			'width' => '55',
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
			'left' => '4',
			'top' => '85',
			'height' => '4',
			'width' => '140',
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
			'left' => '80',
			'top' => '68',
			'height' => '8',
			'width' => '50',
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
			'left' => '80',
			'top' => '35',
			'height' => '10',
			'width' => '60',
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
			'left' => '76',
			'top' => '170',
			'height' => '40',
			'width' => '73'
		));
		return $dimension->id;
	}

	public function logoMerk()
	{
		$dimension = Dimension::create(array(
			'type_id' => DimensionType::LOGO_MERK,
			'left' => '1',
			'top' => '170',
			'height' => '40',
			'width' => '73'
		));
		return $dimension->id;
	}

	
}