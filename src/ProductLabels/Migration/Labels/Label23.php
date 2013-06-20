<?php

namespace ProductLabels\Migration\Labels;

use ProductLabels\Contract\LabelMigrationInterface;
use ProductLabels\Dimension\Dimension;
use ProductLabels\Dimension\DimensionType;
use ProductLabels\Label\Layout;


/**
* Label23
*/
class Label23 implements LabelMigrationInterface
{

	public function layout()
	{
		$layout = Layout::create(array(
			'name' => '105x148 -images (A4) x4',
			'landscape' => '0',
			'is_single_label' => false,
			'width' => '210',
			'height' => '297',
			'widthLabel' => '106',
			'heightLabel' => '149',
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
			'left' => '1',
			'top' => '55',
			'height' => '8',
			'width' => '100',
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
			'top' => '68',
			'height' => '80',
			'width' => '100',
			'font_size' => '10',
			'bold' => '0',
			'max_lines' => '14',
			'fill' => '255,255,255',
			'color' => '0,0,0'
		));
		return $dimension->id;
	}

	public function promotion()
	{
		$dimension = Dimension::create(array(
			'type_id' => DimensionType::PROMOTION,
			'left' => '56',
			'top' => '33',
			'height' => '10',
			'width' => '48',
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
			'left' => '1',
			'top' => '62',
			'height' => '8',
			'width' => '100',
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
			'left' => '53',
			'top' => '48',
			'height' => '8',
			'width' => '48',
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
			'left' => '53',
			'top' => '23',
			'height' => '10',
			'width' => '48',
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
			'left' => '56',
			'top' => '1',
			'height' => '20',
			'width' => '45'
		));
		return $dimension->id;
	}

	
}