<?php
namespace ProductLabels;

use ProductLabels\Contract\ProviderInterface;

class ProductLabelProvider implements ProviderInterface{

	protected $handelaarid;
	protected $queueProvider;
	protected $productProvider;
	protected $pageProvider;
	protected $categoryProvider;

	public function __construct($handelaarid)
	{
		$this->connection = DB::connection('sos');
		$this->handelaarid = $handelaarid;
		$this->queueProvider = new Setup\QueueProvider($this->handelaarid, $this->connection);
		$this->propertyProvider = new Properties\PropertyProvider($this->handelaarid, $this->connection);
		$this->productProvider = new Setup\LabelProductProvider($this->handelaarid, $this->propertyProvider);
		$this->pageProvider = new Pages\PageProvider();
		$this->categoryProvider = new Categories\CategoryProvider();
	}

	public function suggestCategory($query)
	{
		return $this->categoryProvider->suggest($query);
	}

	/**
	 * Fetch the products that are in the queue
	 */
	public function fetchProducts()
	{
		$prodids = $this->queueProvider->fetch();
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

}