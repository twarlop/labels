<?php

namespace ProductLabels\Document;

/**
* Document
*/
class Document
{

	protected $layout;

	protected $pdf;

	/**
	 *	Coordinates for the current label to be displayed.
	 *	Of course this is the top left coordinate of the label
	 *	All dimension coordinates are relative to this point
	 */
	protected $x = 0;
	protected $y = 0;

	public function __construct($pdf, $layout, $pages)
	{
		$this->pdf = $pdf;
		$this->pdf->SetFont('times', '', 10);
		$this->pdf->SetAutoPageBreak(false);
		$this->pdf->SetPrintHeader(false);
		$this->layout = $layout;
		$this->pages = $pages;
		var_dump($layout);
	}

	public function download()
	{
		$this->render();
		$this->pdf->Output('etiketten.pdf', 'D');
	}


	protected function render()
	{
		foreach($this->pages as $page)
		{
			//add a page
			$this->resetCoordinates();
			$this->pdf->AddPage();
			$this->renderPage($page);
		}
	}

	protected function renderPage($page)
	{
		$teller = 1;
		foreach($page as $product)
		{
			$this->calculateCoordinates($teller);
			$this->renderProduct($product);
			$teller++;
		}
	}

	protected function resetCoordinates()
	{
		$this->x = 0;
		$this->y = 0;
	}

	/**
	 * Adjust the coordinates to match the left top of the current label to be displayed
	 * We loop through elements from left to right, top to bottom
	 */
	protected function calculateCoordinates($teller)
	{
		//the y coordinate is determined by the amount of elements that have passed and how many there can be on one line.
		$currentRow = ceil($teller / $this->layout->columns);
		$this->y = ($currentRow - 1) * $this->layout->heightLabel;
		//the x coordinate is determined by how many elements have been shown on the current line.
		$currentColumn = $teller % $this->layout->columns;
		if($currentColumn === 0)
		{
			$currentColumn = intval($this->layout->columns);
		}
		$this->x = ($currentColumn - 1) * $this->layout->widthLabel;
	}

	protected function renderProduct($product)
	{
		foreach($this->layout->dimensions as $dimension)
		{
			$this->setDimension($dimension);
			$this->{'render' . ucfirst($dimension->type->type)}($dimension, $product);
		}
	}

	protected function renderPhoto($dimension, $product)
	{
		$path = $product->getPathPhoto();
		$this->pdf->Image($path, $this->x + $dimension->left, $this->y + $dimension->top, $dimension->width, $dimension->height);
	}

	protected function renderTitle($dimension, $product)
	{
		$this->pdf->Cell($dimension->width, $dimension->height, $product->title);
	}

	protected function renderLogoHandelaar()
	{

	}

	protected function renderLogoMerk()
	{

	}

	protected function renderPrice($dimension, $product)
	{
		if($product->prijs)
		{
			if($product->hasPromotie())
			{
				$this->pdf->writeHtmlCell($dimension->width, $dimension->height, $this->pdf->getX(), $this->pdf->getY(), '<s>€  ' . $product->prijs->prijs . '</s>');
			}
			else{
				$this->pdf->Cell($dimension->width, $dimension->height, '€  ' . $product->prijs->prijs);
			}
		}
	}

	protected function renderPromotion($dimension, $product)
	{
		if($product->hasPromotie())
		{
			$this->pdf->Cell($dimension->width, $dimension->height, '€  ' . $product->promotie->promo);
		}
	}

	public function renderPromotionText($dimension, $product)
	{
		if($product->promotie && $product->promotie->text)
		{
			$this->pdf->Cell($dimension->width, $dimension->height, $product->promotie->promotietext);
		}
	}

	protected function renderText($dimension, $product)
	{
		$this->pdf->Cell($dimension->width, $dimension->height, $product->text);
	}

	/**
	 * Set everything for the pdf for the current dimension
	 */
	protected function setDimension($dimension)
	{
		$this->setFill($dimension->fill);
		$this->setColor($dimension->color);
		$this->setFont($dimension->font_size, true);
		$this->setCoordinates($dimension);
	}

	protected function setCoordinates($dimension)
	{
		$this->pdf->setXY($this->x + $dimension->left, $this->y + $dimension->top);
		// var_dump($this->pdf->getX());
		// var_dump($this->pdf->getY());
		// var_dump($this->x);
	}

	protected function setFont($size, $bold = false)
	{
		if($bold)
		{
			$this->pdf->SetFont('times', 'B', $size);
		}
		else
		{
			$this->pdf->SetFont('times', '', $size);
		}
	}

	protected function setFill($fill)
	{
		list($r, $g, $b) = $this->rgb($fill);
		$this->pdf->SetFillColor($r, $g, $b);
	}

	protected function setColor($color)
	{
		list($r, $g, $b) = $this->rgb($color);
		$this->pdf->SetTextColor($r, $g, $b);
	}

	protected function rgb($color)
	{
		//check if the color is a valid color.
		//hexadecimal form: 6 chars, might be prefixed with #
	}

}