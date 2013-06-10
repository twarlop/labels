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

class PageCollection implements CollectionInterface, Countable, IteratorAggregate
{

	protected $items;

	protected $itemsPerPage;

	/**
	 *	Zero indexed
	 */
	protected $position;

	public function __construct($pages)
	{
		$this->items = $pages;
	}

	public function count()
	{
		return count($this->items);
	}

	public function getIterator()
	{
		return new ArrayIterator($this->items);
	}

	public function toJson()
	{
		return json_encode($this->toArray());
	}

	public function toArray()
	{
		echo 'to implement';
	}

	public function addProduct(LabelProduct $product)
	{
		$page = $this->lastPage();
	}

	public function currentPage()
	{
		$this->items[$this->position];
	}

	public function last()
	{
		return $this->items[count($this->items)-1];
	}

	public function next()
	{
		if($this->position < ($this->count() - 1))
		{
			return $this->items[$this->position];
		}
		else
			return false;
	}

	public function render()
	{
		foreach($this->items as $item)
		{
			$item->render();
		}
	}

}