<?php

namespace ProductLabels\Document;

/**
* Document
*/
class Document
{

	protected $layout;

	protected $pdf;

	public function __construct($pdf, $layout, $pages)
	{
		$this->pdf = $pdf;
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
			$this->pdf->addPage();
			$this->renderPage($page);
		}
	}

	protected function renderPage($page)
	{
		foreach($page as $product)
		{
			$this->renderProduct($product);
		}
	}

	protected function renderProduct($product)
	{
		foreach($this->layout->dimensions as $dimension)
		{
			$this->{'render' . ucfirst($dimension->type->type)}();
		}
	}

	protected function renderPhoto()
	{

	}

	protected function renderTitle()
	{

	}

	protected function renderLogoHandelaar()
	{

	}

	protected function renderLogoMerk()
	{

	}

	protected function renderPrice()
	{

	}

	protected function renderPromotion()
	{

	}

	protected function renderText()
	{
		
	}

}