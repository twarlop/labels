<?php

namespace ProductLabels\Document;

/**
*  EigenschapDocument
*/
class EigenschapDocument extends Document
{

	public function __construct($pdf, $layout, $pages, $propertyProvider)
	{
		$this->propertyProvider = $propertyProvider;
		parent::__construct($pdf, $layout, $pages);
	}

	protected function renderText($dimension, $product)
	{
		$properties = $this->propertiesToRender($dimension, $product);
		$ul = array();
		foreach($properties as $data)
		{
			array_push($ul, $this->li($data['property'], $data['value']));
		}
		$list = '<ul>' . implode('', $ul) . '</ul>';
		$this->pdf->writeHTMLCell($dimension->width, $dimension->height, $this->x + $dimension->left, $this->y + $dimension->top, $list);
	}

	protected function li($property, $value)
	{
		return '<li>' . $property->invoernl . ': ' . $value['inhoudnl'] . '</li>';
	}

	/**
	 * Take properties from the front, to preserve order that the customer selected
	 */
	protected function propertiesToRender($dimension, $product)
	{
		$answer = array();
		$properties = $this->propertyProvider->propertiesFromMap($product);
		$maxLines = $this->maxLines($dimension, $product);
		while(count($answer) < $maxLines && count($properties) > 0)
		{
			array_push($answer, array_shift($properties));
		}
		return $answer;
	}

	/**
	 * We use this function instead of the original, 
	 * because lines are to close to the bottom when using <ul></ul>
	 * This is also usefull, if there would be a property that needs more than 1 line, 
	 * we have this removed line as a safety measure
	 */
	protected function maxLines($dimension, $product)
	{
		$max_lines = intval($dimension->max_lines);
		$max_lines--;
		return $max_lines;
	}
}