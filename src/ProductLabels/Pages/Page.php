<?php
namespace ProductLabels\Pages;
use IterarorAggregate;
use Countable;
use ArrayAccess;
use ProductLabels\Contract\CollectionInterface;
/**
* Page
*
* Holds the collection of products that is needed on a page.
* It also has some functionality to calculate weither or not the page has more room to add another product etc
*/
class Page
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