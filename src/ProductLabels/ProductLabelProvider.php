<?php
namespace ProductLabels;

use ProductLabels\Contract\ProviderInterface;

class ProductLabelProvider implements ProviderInterface{

	protected $connection;
	protected $handelaarid;
	protected $queueProvider;
	protected $propertyProvider;
	protected $productProvider;
	protected $pageProvider;
	protected $categoryProvider;
	protected $labelProvider;
	protected $documentProvider;

	public function __construct($handelaarid)
	{
		$this->connection = DB::connection('sos');
		$this->handelaarid = $handelaarid;
		$this->labelProvider = new Label\LabelProvider();
		$this->queueProvider = new Setup\QueueProvider($this->handelaarid, $this->connection);
		$this->propertyProvider = new Properties\PropertyProvider($this->handelaarid, $this->connection);
		$this->productProvider = new Setup\LabelProductProvider($this->handelaarid, $this->propertyProvider, $this->labelProvider);
		$this->categoryProvider = new Categories\CategoryProvider();
		$this->labelProvider = new Label\LabelProvider();
	}

	public function suggestCategory($query)
	{
		return $this->categoryProvider->suggest($query);
	}

	public function suggestProducts($query)
	{
		$excludeIds = $this->queueProvider->fetchProdids();
		$products = $this->productProvider->suggest($query, $excludeIds);
		return $products;
	}

	/**
	 * Fetch the products that are in the queue
	 */
	public function fetchProducts()
	{
		$prodids = $this->queueProvider->fetchProdids();
		$products = $this->productProvider->find($prodids);
		return $products;
	}

	public function fetchSortingsForCategory($category_id)
	{
		$standardProperties = $this->propertyProvider->fetchStandardPropertyOrder($category_id);
		$customProperties = $this->propertyProvider->fetchCustomPropertyOrder($category_id);
		return array(
			'custom' => $customProperties->toArray(),
			'standard' => $standardProperties->toArray()
		);
	}

	public function sync($categoryId, $properties)
	{
		$this->propertyProvider->sync($categoryId, $properties);
	}

	public function queue($prodid)
	{
		$this->queueProvider->queue($prodid);
		$product = $this->productProvider->findById($prodid);
		return $product;
	}

	public function dequeue($prodid)
	{
		$this->queueProvider->dequeue($prodid);
	}

	public function reloadProduct($prodid)
	{
		$product = $this->productProvider->findById($prodid);
		return $product;
	}

	public function clearQueue()
	{
		$this->queueProvider->clear();
	}

	public function fetchAfmetingen()
	{
		$layouts = $this->labelProvider->fetchAfmetingen();
		return $layouts;
	}

	public function downloadPdf()
	{
		$pageProvider = new Pages\PageProvider($this->handelaarid);
		$documentProvider = new Document\DocumentProvider($this->handelaarid, $pageProvider, $this->labelProvider, $this->propertyProvider);
		$document = $documentProvider->createDocument($this->fetchProducts());
		$document->download();
	}

}