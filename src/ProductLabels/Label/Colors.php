<?php
namespace ProductLabels\Label;

/**
* Colors
*/
class Colors extends Eloquent
{

	protected $table = 'handelaars_label_colors';

	protected $fillable = array('handelaarid', 'normal', 'prijs', 'prijs_bg', 'promo', 'promo_bg', 'titel', 'titel_bg');
	
}