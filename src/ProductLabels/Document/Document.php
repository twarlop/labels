<?php

namespace ProductLabels\Document;

/**
* Document
*/
abstract class Document
{

	protected $layout;

	protected $pdf;

	protected $pages;

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
		$this->pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$this->layout = $layout;
		$this->pages = $pages;
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
			$this->cuttingLines();
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
		// $this->pdf->Image($path, $this->x + $dimension->left, $this->y + $dimension->top, $dimension->width, $dimension->height, '', '', '', false);
		$this->pdf->Image($path, '', '', $dimension->width, $dimension->height, '', '', '', true);
		$this->pdf->Cell($dimension->width, $dimension->height, '', 1);
	}

	protected function renderTitle($dimension, $product)
	{
		$this->pdf->Cell($dimension->width, $dimension->height, $product->title, 0, 0, 'C', 1);
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
			$this->pdf->Cell($dimension->width, $dimension->height, '€  ' . $product->promotie->promo, 1, 0, 'C', 1);
		}
	}

	public function renderPromotionText($dimension, $product)
	{
		if($product->promotie && $product->promotietext)
		{
			$this->pdf->Cell($dimension->width, $dimension->height, $product->promotietext);
		}
	}

	public function renderPromotionStop($dimension, $product)
	{
		if($product->hasPromotie() && $product->promotie->stop)
		{
			$this->pdf->Cell($dimension->width, $dimension->height, $product->promotie->stop);
		}
	}

	abstract protected function renderText($dimension, $product);

	/**
	 * Set everything for the pdf for the current dimension
	 */
	protected function setDimension($dimension)
	{
		$this->setFill($dimension->fill);
		$this->setColor($dimension->color);
		$this->setFont($dimension->font_size, $dimension->bold);
		$this->setCoordinates($dimension);
	}

	protected function setCoordinates($dimension)
	{
		$this->pdf->setXY($this->x + $dimension->left, $this->y + $dimension->top);
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
		if(!$fill)
		{
			$fill = '0,0,0';
		}
		list($r, $g, $b) = $this->rgb($fill);
		$this->pdf->SetFillColor($r, $g, $b);
	}

	protected function setColor($color)
	{
		if(!$color)
		{
			$color = '255,255,255';
		}
		list($r, $g, $b) = $this->rgb($color);
		$this->pdf->SetTextColor($r, $g, $b);
	}

	protected function rgb($color)
	{
		$color = explode(',', $color);
		return $color;
	}


	protected function cellPadding()
	{
		$margins = $this->pdf->getMargins();
		return $margins['cell']['L'] + $margins['cell']['R'];
	}

	protected function toWords($tekst)
	{
		$tekst = preg_split('/\s/', $tekst);
		return $tekst;
	}

	protected function toText($tekst)
	{
		$tekst = preg_replace("/\n\s/", "\n", $tekst);
		return $tekst;
	}

	protected function maxLines($dimension, $product)
	{
		$max_lines = intval($dimension->max_lines);
		if($product->hasPromotie())
		{
			if($product->promotietext)
			{
				$max_lines--;
			}
		}
		return $max_lines;
	}

	protected function cuttingLines()
	{
		$this->pdf->setXY($this->x, $this->y);
		$this->pdf->SetLineStyle(array('width' => 0.0001));
		$this->pdf->Cell($this->layout->widthLabel, $this->layout->heightLabel, null, 1);
	}

}