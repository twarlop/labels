<?php
namespace ProductLabels\Document;

use ProductLabels\Contract\ProviderInterface;
use ProductLabels\Pages\PageCollection;
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
		$this->labelProvider = $labelProvider;
		$this->pageProvider = $pageProvider;
	}

	protected function layout()
	{
		return $this->labelProvider->fetchLayout();
	}

	public function createDocument($products)
	{
		$layout = $this->layout();
		$pdf = new TCPDF;
		$document = new Document($pdf, $layout, $this->pageProvider->collection($layout->itemsPerPage(), $products));
		return $document;
	}
	
}