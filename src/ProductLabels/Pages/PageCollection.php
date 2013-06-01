<?php
namespace ProductLabels\Pages;

use ProductLabels\Contract\CollectionInterface;
use ProductLabels\Setup\LabelProduct;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use ArrayAccess;

/**
* PageCollection
*
* A collection of pages that will be passed to a document, each page has its own products
*/

class PageCollection implements CollectionInterface, ArrayAccess, Countable, IteratorAggregate
{

	protected $items;

	public function __construct(array $items = array())
	{
		$this->items = $items;
	}

	public function offsetExists($offset)
	{
		return isset($this->items[$offset]);
	}

	public function offsetSet($offset, $value)
	{
		$this->items[$offset] = $value;
	}

	public function offsetGet($offset)
	{
		return $this->items[$offset];
	}

	public function offsetUnset($offset)
	{
		unset($this->items[$offset]);
	}

	public function count()
	{
		return count($this->items);
	}

	public function getIterator()
	{
		return new ArrayIterator($this->items);
	}

}