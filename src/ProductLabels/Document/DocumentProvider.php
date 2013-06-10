<?php
namespace ProductLabels\Document;

use ProductLabels\Contract\ProviderInterface;
use TCPDF;

/**
* Document
*
* This is the superobject that hold all the necessary stuff to build our pdf document.
*/
class DocumentProvider implements ProviderInterface
{
	/**
	 * 
	 * @var ProductLabels\Label\LabelProvider
	 */
	protected $labelProvider;

	/**
	 * @var ProductLabels\Pages\PageProvider
	 */
	protected $pageProvider;

	protected $handelaar_id;

	/**
	 * @var TCPDF;
	 */
	protected $pdf;

	public function __construct($handelaar_id, $pageProvider, $labelProvider)
	{
		$this->handelaar_id = $handelaar_id;
		$this->labelProvider = $pageProvider;
		$this->pageProvider = $labelProvider;
	}

	protected function init()
	{
		$this->pdf = new TCPDF();
		$this->layout = $this->labelProvider->fetchLayout($this->layout->id);
		$this->pages = $this->pageProvider->loadPages($this->layout, $this->handelaar_id);
	}

	public function download()
	{
		$this->init();
		$this->pdf->Output('etiketten.pdf', 'D');
	}
	
}