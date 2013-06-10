<?php
namespace ProductLabels\Label;

use ProductLabels\Contract\ProviderInterface;

/**
* LabelProvider
*/
class LabelProvider implements ProviderInterface
{

	protected $layout;
	protected $layoutDimension;

	public function __construct()
	{
		$this->layout = new Layout();
		$this->layoutDimension = new LayoutDimension();
	}

	public function fetchAfmetingen()
	{
		$layouts = $this->layout->all();
		return $layouts;
	}

	/**
	 *
	 */
	public function fetchLayout($layoutid)
	{
		$this->layout->find($layoutid);
	}

}