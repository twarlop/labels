<?php

namespace ProductLabels\Setup;
use Exception;

/**
* LabelProduct
*
* This is supposed to be a class that will fetch the products that need to be printed
* We get them out of the print queue that is defined by the handelaar
*/
class LabelProduct
{
	
	protected $product_id;
	protected $merkid;
	protected $merknaam;
	protected $category_id;
	protected $category;
	protected $title;
	protected $photo;
	protected $prijs;
	protected $promotie;
	protected $promotietext;
	protected $text;
	protected $logoMerk;
	protected $logoHandelaar;
	protected $customLabel;
	protected $properties = array();

	public function __construct(\stdClass $data)
	{
		foreach($data as $k => $v)
		{
			$this->$k = $v;
		}
	}

	public function __get($name)
	{
		return $this->$name;
	}

	/**
	 * Only set prijs when there is no price set yet.
	 * The LabelProductProvider makes sure that we try to set prices in the right order.
	 * This means, first set all hand prijzen, then all groepprijzen if none set yet, and at last
	 * all fab prijzen.
	 */
	public function setPrijs($prijs)
	{
		if(empty($this->prijs))
			$this->prijs = $prijs;
	}

	/**
	 * Only try setting promotie if there was none set yet.
	 * If none was set: only set it when this level is either
	 * -> the same or higher then the level of the price for the product
	 * @param [type] $promotie [description]
	 */
	public function setPromotie($promotie, $taal)
	{
		if(empty($this->promotie))
		{
			if($this->prijs)
			{
				$type = $this->prijs->type;
				switch($type)
				{
					case 'hand':
						if($promotie->type === 'hand')
						{
							$this->promotie = $promotie;
							$this->setPromotieTekst($promotie, $taal);
						}
					break;
					case 'groep':
						if($promotie->type === 'groep' || $promotie->type === 'hand'){
							$this->promotie = $promotie;
							$this->setPromotieTekst($promotie, $taal);
						}
					break;
					case 'fab':
						$this->promotie = $promotie;
						$this->setPromotieTekst($promotie, $taal);
					break;
				}
			}
		}
	}



	/**
	 * expects a key-value array with the locale being the key, and the value being the custom label for that locale
	 * @param array $label [description]
	 */
	public function setCustomLabel(array $labels)
	{
		$locales = array('nl', 'fr');
		foreach($labels as $locale => $label)
		{
			if(!in_array($locale, $locales))
			{
				throw new Exception('Invalid locale provided');
			}
		}
		$this->customLabel = $labels;
	}

	public function hasCustomLabel()
	{
		return is_array($this->customLabel);
	}

	public function addProperty($property)
	{
		$this->properties[$property->catinvoerveldid] = $property;
	}

	public function getProperties()
	{
		return $this->properties;
	}

	public function toJson()
	{
		$properties = get_object_vars($this);
		$json = array();
		foreach($properties as $property => $value)
		{
			if(is_object($value))
			{
				$json[$property] = $value->toJson();
			}
			else
			{
				$json[$property] = $value;
			}
		}
		return json_encode($json);
	}

	public function hasPromotie()
	{
		return !empty($this->promotie);
	}

	public function getPathPhoto()
	{
		$base = $_SERVER['DOCUMENT_ROOT'] . 'images/ez_prod/' . $this->merkid . '/' . $this->product_id . '/';
		if(is_file($base . $this->photo))
			return $base . $this->photo;
	}

	public function textToPrint($language)
	{
		if($this->customLabel[$language])
			return $this->customLabel[$language];
		else
		{
			return $this->text[$language];
		}	
	}

	protected function setPromotieTekst($promotie, $taal)
	{
		if($taal === 'nl')
		{
			$this->promotietext = $promotie->tekstnl;
		}
		else if($taal === 'fr')
		{
			$this->promotietext = $promotie->tekstfr;
		}
	}

	public function getPathMerk()
	{
		$path = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . "/images/merken/" . $this->merkid . '/foto2/orig/' . $this->logoMerk; 
		if(is_file($path))
			return $path;
		return false;
	}

}