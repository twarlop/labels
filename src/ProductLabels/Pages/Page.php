<?php
namespace ProductLabels\Pages;
use IterarorAggregate;
use IteratorAggregate;
use ProductLabels\Contract\CollectionInterface;
use TCPDF;
use ArrayIterator;
/**
* Page
*
* Holds the collection of products that is needed on a page.
* It also has some functionality to calculate weither or not the page has more room to add another product etc
*/
class Page implements IteratorAggregate
{
	
	protected $items = array();

	public function __construct()
	{
	}

	public function getIterator()
	{
		return new ArrayIterator($this->items);
	}

	public function addProduct($product)
	{
		array_push($this->items, $product);
	}
	
}