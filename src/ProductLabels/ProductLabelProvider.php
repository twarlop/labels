<?php
namespace ProductLabels;

use ProductLabels\Contract\ProviderInterface;


class ProductLabelProvider implements ProviderInterface{

	protected $handelaarid;
	protected $queueProvider;
	protected $productProvider;
	protected $pageProvider;

	public function __construct($handelaarid)
	{
		$this->handelaarid = $handelaarid;
		$this->queueProvider = new Setup\QueueProvider($this->handelaarid);
		$this->productProvider = new Setup\LabelProductProvider($this->handelaarid);
		$this->propertyProvider = new Properties\PropertyProvider($this->handelaarid);
		$this->pageProvider = new Pages\PageProvider();
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

	protected function categories(array $products)
	{
		$categories = array_map(function($product){
			return $product->categorie_id;
		}, $products);
		return array_values($categories);
	}

}