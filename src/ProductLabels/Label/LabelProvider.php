<?php
namespace ProductLabels\Label;

use ProductLabels\Contract\ProviderInterface;
use Exception;

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

	protected $disclaimer_nl;
	protected $disclaimer_fr;

	public function __construct()
	{
		$this->setupSettings();
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
	public function fetchLayout()
	{
		$layout = $this->layout->with(array('dimensions', 'dimensions.type'))->find($this->type);
		return $layout;
	}

	protected function setupSettings()
	{
		global $SETTINGS;
		$this->type = $SETTINGS['label_type2']->getValue();
		$this->taal = $SETTINGS['label_taal2']->getValue();
		$this->mode = $SETTINGS['label_mode2']->getValue();
		$this->disclaimer_nl = $SETTINGS['label_disclaimer_nl']->getValue();
		if($this->disclaimer_nl === '')
			$this->disclaimer_nl = false;
		$this->disclaimer_fr = $SETTINGS['label_disclaimer_fr']->getValue();
		if($this->disclaimer_fr === '')
			$this->disclaimer_fr = false;
	}

	public function getMode()
	{
		switch($this->mode)
		{
			case '3':
			case '1':
				return 'text';
			break;
			case '2':
				return 'properties';
			break;
			default:
				throw new Exception('Invalid mode');
			break;
		}
	}

	public function getTaal()
	{
		switch(intval($this->taal))
		{
			case 1:
				return 'nl';
			break;
			case 2:
				return 'fr';
			break;
		}
	}

	public function getDisclaimer()
	{
		switch(intval($this->taal)){
			case 1:
				return $this->disclaimer_nl;
			break;

			case 2:
				return $this->disclaimer_fr;
			break;
		}
	}

}