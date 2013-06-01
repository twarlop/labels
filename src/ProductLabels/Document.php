<?php
namespace ProductLabels;

/**
* Document
*
* This is the superobject that hold all the necessary stuff to build our pdf document.
*/
class Document
{
	/**
	 * 
	 * @var ProductLabels\Layout
	 */
	protected $layout;

	/**
	 * @var ProductLabels\Pages\PageCollection
	 */
	protected $pageCollection;
	
}