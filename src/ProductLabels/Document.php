<?php
namespace ProductLabels;

use ProductLabels\Label\LabelProvider;
use ProductLabels\Pages\PageProvider;
use TCPDF;

/**
* Document
*
* This is the superobject that hold all the necessary stuff to build our pdf document.
*/
class Document
{
	/**
	 * 
	 * @var ProductLabels\Label\LabelProvider
	 */
	protected $layout;

	/**
	 * @var ProductLabels\Pages\PageProvider
	 */
	protected $pageProvider;

	/**
	 * @var TCPDF;
	 */
	protected $pdf;

	public function __construct()
	{
		$this->labelProvider = new LabelProvider();
		$this->pageProvider = new PageProvider();
		$this->init();
	}

	protected function init()
	{
		$this->pdf = new TCPDF();
	}

	public function download()
	{
		$this->pdf->Output('etiketten.pdf', 'D');
	}
	
}