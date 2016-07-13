<?php

namespace App;
use App\DietMenu;

class DietHelper 
{
	public function menus($class, $period, $week, $day)
	{

			$menu_products = DietMenu::where('class_code',$class)
								->where('period_code', $period)
								->get();

			return $menu_products;			

	}

}

