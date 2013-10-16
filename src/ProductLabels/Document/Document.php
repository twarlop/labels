<?php

namespace ProductLabels\Document;

/**
* Document
*/
class Document
{

	protected $layout;

	protected $pdf;

	protected $pages;

	protected $propertyProvider;

	protected $categoryProvider;

	/**
	 * Text or eigenschappen?
	 * This is the global setting and might be overriden by the category setting itself
	 */
	protected $mode;

	protected $language;

	protected $disclaimer;

	/**
	 *	Coordinates for the current label to be displayed.
	 *	Of course this is the top left coordinate of the label
	 *	All dimension coordinates are relative to this point
	 */
	protected $x = 0;
	protected $y = 0;

	public function __construct($pdf, $layout, $pages, $propertyProvider, $categoryProvider, $labelProvider)
	{
		$this->pdf = $pdf;
		$this->pdf->SetFont('helvetica', '', 10);
		$this->pdf->SetAutoPageBreak(false);
		$this->pdf->SetPrintHeader(false);
		$this->pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$this->layout = $layout;
		$this->pages = $pages;
		$this->mode = $labelProvider->getMode();
		$this->language = $labelProvider->getTaal();
		$this->disclaimer = $labelProvider->getDisclaimer();
		$this->categoryProvider = $categoryProvider;
		$this->propertyProvider = $propertyProvider;
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
		$this->pdf->Image($path, $this->x + $dimension->left, $this->y + $dimension->top, $dimension->width, $dimension->height, '', '', '', false, 300, '', false, false, 0, 'CM');
		// $this->pdf->Cell($dimension->width, $dimension->height, '', 1);
	}

	protected function renderTitle($dimension, $product)
	{
		$this->pdf->Cell($dimension->width, $dimension->height, $product->title, 0, 0, 'C', 1);
	}

	protected function renderLogoHandelaar($dimension)
	{
		$path = $this->getHandelaarPath();
		if($path){
			$this->pdf->Image($path, $this->x + $dimension->left, $this->y + $dimension->top, $dimension->width, $dimension->height, '', '', '', false, 300, '', false, false, 0, 'CM');
			// $this->pdf->Cell($dimension->width, $dimension->height, '', 1);
		}
	}

	protected function renderLogoMerk($dimension, $product)
	{
		$path = $product->getPathMerk();
		if($path)
		{
			$this->pdf->Image($path, $this->x + $dimension->left, $this->y + $dimension->top, $dimension->width, $dimension->height, '', '', '', false, 300, '', false, false, 0, 'CM');
			// $this->pdf->Cell($dimension->width, $dimension->height, '', 1);
		}
	}

	protected function renderPrice($dimension, $product)
	{
		if($product->prijs)
		{
			if($product->hasPromotie())
			{
				$this->setFont($dimension->font_size - 4, $dimension->bold);
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
			$this->pdf->Cell($dimension->width, $dimension->height, '€  ' . $product->promotie->promo, 0, 0, 'C', 1);
		}
	}

	public function renderPromotionText($dimension, $product)
	{
		if($product->promotie && $product->promotietext)
		{
			$text = $this->trimRegularText(1, $product->promotietext, $dimension->width);
			$this->pdf->Cell($dimension->width, $dimension->height, $text);
		}
	}

	public function renderPromotionStop($dimension, $product)
	{
		if($product->hasPromotie() && $product->promotie->stop)
		{
			$this->pdf->Cell($dimension->width, $dimension->height, $product->promotie->stop);
		}
	}

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
			$this->pdf->SetFont('helvetica', 'B', $size);
		}
		else
		{
			$this->pdf->SetFont('helvetica', '', $size);
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
		if($this->disclaimer)
			$max_lines--;
		return $max_lines;
	}

	/**
	 * We use this function instead of the original, 
	 * because lines are to close to the bottom when using <ul></ul>
	 * This is also usefull, if there would be a property that needs more than 1 line, 
	 * we have this removed line as a safety measure
	 */
	protected function maxLinesProperties($dimension, $product)
	{
		$max_lines = intval($dimension->max_lines);
		$max_lines--;
		if($this->disclaimer)
			$max_lines--;
		return $max_lines;
	}

	/**
	 * Verify if the amount of properties fit the current dimension, taking into account that there might be 
	 * properties which need more then 1 line to display.
	 */
	protected function confirmMaxlinesProperties($properties, $dimension)
	{
		$skip = 0;
		$padding = $this->pdf->getCssPadding('40px');
		$w = $dimension->width - $padding['L'];
		foreach($properties as $property)
		{
			$li = $this->li($property['property'], $property['value']);
			preg_match_all('#<li>(.*)</li>#', $li, $matches);
			$text = $matches[1][0];
			$wText = $this->pdf->GetStringWidth($text);
			if($wText > $w)
			{
				$skip++;
			}
		}
		if($skip)
		{
			$properties = array_slice($properties, 0, count($properties) - $skip);
		}
		return $properties;
	}

	protected function cuttingLines()
	{
		$this->pdf->setXY($this->x, $this->y);
		$this->pdf->SetLineStyle(array('width' => 0.0001, 'color' => array(224, 224, 224)));
		$this->pdf->Cell($this->layout->widthLabel, $this->layout->heightLabel, null, 1);
	}

	/**
	 * Custom labels have priority
	 */
	protected function renderText($dimension, $product)
	{
		$mode = $this->getMode($product, $dimension);

		switch($mode)
		{
			case 'text':
				$maxLines = $this->maxLines($dimension, $product);
				$text = $this->trimRegularText($maxLines, $product->textToPrint($this->language), $dimension->width);
				if($this->disclaimer)
				{
					$text .= '<div style=""><b>' . $this->disclaimer . '</b></div>';
				}
				// $this->pdf->MultiCell($dimension->width, $dimension->height, $text, 0, 'L');
				$this->pdf->writeHTMLCell($dimension->width, $dimension->height, $this->x + $dimension->left, $this->y + $dimension->top, $text);
			break;
			case 'properties':
				$properties = $this->propertiesToRender($dimension, $product);
				$ul = array();
				foreach($properties as $data)
				{
					array_push($ul, $this->li($data['property'], $data['value']));
				}
				$list = '<ul>' . implode('', $ul) . '</ul>';
				$this->pdf->writeHTMLCell($dimension->width, $dimension->height, $this->x + $dimension->left, $this->y + $dimension->top, $list);
				if($this->disclaimer)
				{
					$this->pdf->writeHTMLCell($dimension->width, $dimension->height, $this->x + $dimension->left, $this->y + $dimension->top + $dimension->height - 8, '<div style=""><strong>' . $this->disclaimer . '</strong></div>');
				}
			break;
		}
	}

	/**
	 * Returns the mode for the current label
	 * If the category of the product has a mode set, we use that one instead of the global one.
	 * If the product has a custom label -> we always use the text mode!
	 */
	protected function getMode($product, $dimension)
	{
		//global mode
		$mode = $this->mode;
		//cat specific mode
		$mode = $this->categoryProvider->getInfoType($product->category_id);
		//if empty properties -> set mode to text
		$properties = $this->propertiesToRender($dimension, $product);
		if($mode === 'properties' && empty($properties))
		{
			$mode = 'text';
		}
		if($product->hasCustomLabel())
		{
			$mode = 'text';
		}
		if(empty($mode))
			$mode = $this->mode;
		return $mode;
	}

	protected function trimRegularText($maxLines, $tekst, $lineWidth)
	{
		$line = 1;
		$words = $this->toWords($tekst);
        $lineString = '';
        $lastWordIndex = 0;
        for ($i = 0; $i < count($words); $i++) {
            if ($line <= $maxLines) {
                $curString = $lineString;
                if($words[$i] === ''){
                    //this value represents a new line
                    $line++;
                    $lineString = '';
                    $lastWordIndex = $i;
                    $words[$i] = "<br/>";
                }
                else{
                    if ($lineString != '')
                        $lineString .= ' ' . $words[$i]; //only add space if its not the first word on the line
                    else
                        $lineString .= $words[$i];
                    if ($this->pdf->GetStringWidth($lineString) + ($this->cellPadding()) > $lineWidth) {
                        $line++;
                        $lineString = $words[$i];
                        if ($line < $maxLines)
                            $lastWordIndex = $i;
                    }
                    else
                        $lastWordIndex = $i;
                }
            }
        }
        if ($lastWordIndex + 1 < count($words))
            $words = implode(' ', array_slice($words, 0, $lastWordIndex)) . '...'; // also pop last correct word to avoid a new line caused by ...
        else
            $words = implode(' ', $words);
        $tekst = $this->toText($words);
        return $tekst;
	}

	protected function li($property, $value)
	{
		switch ($this->language) {
			case 'nl':
				return '<li>' . $property->invoernl . ': ' . $value['inhoudnl'] . '</li>';
				break;
			case 'fr':
				return '<li>' . $property->invoerfr . ': ' . $value['inhoudfr'] . '</li>';
				break;
			
		}
	}

	/**
	 * Take properties from the front, to preserve order that the customer selected
	 */
	protected function propertiesToRender($dimension, $product)
	{
		$answer = array();
		$properties = $this->propertyProvider->propertiesFromMap($product, $this->language);
		$maxLines = $this->maxLinesProperties($dimension, $product);
		while(count($answer) < $maxLines && count($properties) > 0)
		{
			array_push($answer, array_shift($properties));
		}
		$answer = $this->confirmMaxlinesProperties($answer, $dimension);
		return $answer;
	}

	protected function getHandelaarPath()
	{
		global $HANDINFO;
		$sos_pagina = $HANDINFO['sos_pagina'];
		$path = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . "/shops/" . $sos_pagina . "/images/logo_shop.png";
        $alterPath = preg_replace('/\/images\//', '/img/', $path);
        if(is_file($path))
        {
            return $path;
        }
        else if(is_file($alterPath))
        {
            return $alterPath;
        }
        else
        {
            return false;
        }
	}

}