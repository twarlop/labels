<?php
namespace ProductLabels\Pages;

use ProductLabels\Contract\ProviderInterface;

/**
* PageFactory
*
* Factory that will help us build the necesarry pages and page collections
*/
class PageProvider implements ProviderInterface
{

	protected $handelaar_id;

	public function __construct($handelaar_id)
	{
		$this->handelaar_id = $handelaar_id;
	}

	public function collection($itemsPerPage, $products)
	{
		$pages = $this->paginate($itemsPerPage, $products);
		$collection = new PageCollection($pages);
		return $collection;
	}

	protected function paginate($itemsPerPage, $products)
	{
		$answer = [];
		$count = 0;
		$pageCount = 0;
		while($count < $itemsPerPage && count($products))
		{
			$product = array_pop($products);
			if($count === 0)
			{
				$pageCount ++;
				$page = new Page();
				array_push($answer, $page);
			}
			else
			{
				$page = $answer[$pageCount - 1];
			}
			$page->addProduct($product);
			//make sure loop goes correct
			$count++;
			if($count == $itemsPerPage)
			{
				$count = 0;
			}
		}
		return $answer;
	}	

}