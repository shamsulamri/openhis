<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Session;
use App\Diet;
use App\DietMenu;
use App\DietClass;
use App\DietPeriod;
use App\DietHelper;

class DietMenuController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index($class, $period, $week, $day)
	{

			$menu_products = DietMenu::where('class_code',$class)
								->where('period_code', $period)
								->get();
			
			return view('diet_menus.index',[
					'menu_products'=>$menu_products,
					'class'=>DietClass::where('class_code',$class)->first(),
					'period'=>DietPeriod::where('period_code',$period)->first(),
					'week'=>$week,
					'day'=>$day,
			]);

	}

	public function menu(Request $request)
	{
			
			$dt = Carbon::now();
			if (empty($request->dayOfWeek)) {
					$dayOfWeek = $dt->dayOfWeek;
			} else {
					$dayOfWeek = $request->dayOfWeek;
			}
			if (empty($rquest->weekOfMonth)) {
					$weekOfMonth = $dt->weekOfMonth;
			} else {
					$weekOfMonth = $request->weekOfMonth;
			}

			if ($weekOfMonth>4) $weekOfMonth=1;

			$diet_code = "normal";
			$class_code = "class1";
			return view('diet_menus.menu', [
					'diets' => Diet::all()->sortBy('diet_name')->lists('diet_name', 'diet_code'),
					'diet_classes' => DietClass::where('diet_code',$diet_code)->orderBy('class_position')->get(),
					'diet_code' => $diet_code,
					'class_code' => $class_code, 
					'weekOfMonth' => $weekOfMonth,
					'dayOfWeek' => $dayOfWeek,
					'weeks' => array('1'=>'Week 1','2'=>'Week 2','3'=>'Week 3','4'=>'Week 4'),
					'days' => array('1'=>'Monday','2'=>'Tuesday','3'=>'Wednesday','4'=>'Thursdays','5'=>'Friday','6'=>'Saturday','7'=>'Sunday'),
					'diet_periods' => DietPeriod::all()->sortBy('period_position'),
					'dietHelper' => new DietHelper(),
			]);
	}


	public function create(Request $request, $class, $period, $week, $day)
	{
			return view('diet_menus.create',[
					'class_code'=>$class,
					'period_code'=>$period,
					'week'=>$week,
					'day'=>$day,
			]);

	}


}
