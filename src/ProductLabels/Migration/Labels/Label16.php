<?php

namespace ProductLabels\Migration\Labels;

use ProductLabels\Contract\LabelMigrationInterface;
use ProductLabels\Dimension\Dimension;
use ProductLabels\Dimension\DimensionType;
use ProductLabels\Label\Layout;


/**
* Label16
*/
class Label16 implements LabelMigrationInterface
{

	public function layout()
	{
		$layout = Layout::create(array(
			'id' => '16',
			'name' => '165x36 (Label)',
			'landscape' => '1',
			'is_single_label' => false,
			'width' => '36',
			'height' => '165',
			'widthLabel' => '165',
			'heightLabel' => '36',
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
			'left' => '46',
			'top' => '1',
			'height' => '8',
			'width' => '123',
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
			'left' => '46',
			'top' => '23',
			'height' => '8',
			'width' => '35',
			'font_size' => '25',
			'bold' => '1',
			'fill' => '255,255,255',
			'color' => '255,0,0'
		));
		return $dimension->id;
	}

	public function promotionText()
	{

	}

	public function promotionStop()
	{
		$dimension = Dimension::create(array(
			'type_id' => DimensionType::PROMOTION_STOP,
			'left' => '93',
			'top' => '8',
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
			'left' => '46',
			'top' => '12',
			'height' => '10',
			'width' => '35',
			'font_size' => '25',
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
			'top' => '15',
			'height' => '20',
			'width' => '70'
		));
		return $dimension->id;
	}

	public function logoMerk()
	{

	}

	
}