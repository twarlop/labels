<?php
namespace ProductLabels;

/**
* Layout
*
* Holds a property for each possible dimension that is valid for the labels to be used on the pages
*
* example: this will not hold a photo dimension if the current layout to be printed doens't need a photo
*/
class Layout
{

	/**
	 * @var ProductLabels\TextDimension
	 */
	protected $content;

	/**
	 * @var ProductLabels\TextDimension
	 */
	protected $title;

	/**
	 * @var ProductLabels\PhotoDimension
	 */
	protected $photo;

	/**
	 * @var ProductLabels\TextDimension
	 */
	protected $prijs;

	/**
	 * @var ProductLabels\TextDimension
	 */
	protected $promotie;

	/**
	 * @var ProductLabels\TextDimension
	 */
	protected $promotieText;

	/**
	 * @var ProductLabels\PhotoDimension
	 */
	protected $logoMerk;

	/**
	 * @var ProductLabels\PhotoDimension
	 */
	protected $logoHandelaar;


}