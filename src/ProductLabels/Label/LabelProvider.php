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

	/**
	 *	Kind of info on the label
	 */
	protected $type;

	/**
	 *	Language on the label
	 */
	protected $taal;

	/**
	 *	Number of the layout
	 */
	protected $mode;

	public function __construct()
	{
		$this->setupSettings();
		$this->layout = new Layout();
		$this->layoutDimension = new LayoutDimension();
		var_dump($this);
	}

	public function fetchAfmetingen()
	{
		$layouts = $this->layout->all();
		return $layouts;
	}

	/**
	 *
	 */
	public function fetchLayout()
	{
		$layout = $this->layout->with(array('dimensions', 'dimensions.type'))->find($this->mode);
		return $layout;
	}

	protected function setupSettings()
	{
		global $SETTINGS;
		$this->type = $SETTINGS['label_type']->getValue();
		$this->taal = $SETTINGS['label_taal']->getValue();
		$this->mode = $SETTINGS['label_mode']->getValue();
	}

}