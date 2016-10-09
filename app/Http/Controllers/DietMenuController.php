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
use App\Ward;
use Log;

class DietMenuController extends Controller
{
	public $paginateValue=10;
	public $days = array('1'=>'Monday','2'=>'Tuesday','3'=>'Wednesday','4'=>'Thursdays','5'=>'Friday','6'=>'Saturday','0'=>'Sunday');
	public $weeks = array('1'=>'Week 1','2'=>'Week 2','3'=>'Week 3','4'=>'Week 4');

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index($class, $period, $week, $day)
	{

			$menu_products = DietMenu::where('class_code',$class)
								->where('period_code', $period)
								->where('week_index', $week)
								->where('day_index', $day)
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
			if (empty($request->refresh)) {
					$dayOfWeek = $dt->dayOfWeek;
			} else {
					$dayOfWeek = $request->dayOfWeek;
			}
			if (empty($request->weekOfMonth)) {
					$weekOfMonth = $dt->weekOfMonth;
			} else {
					$weekOfMonth = $request->weekOfMonth;
			}

			if ($weekOfMonth>4) $weekOfMonth=1;

			$diet_code = "normal";
			$class_code = "class1";

			if (!empty($request->diet_code)) $diet_code = $request->diet_code;
			if (!empty($request->class_Code)) $class_code = $request->class_code;

			Log::info($dayOfWeek);
			return view('diet_menus.menu', [
					'diets' => Diet::all()->sortBy('diet_name')->lists('diet_name', 'diet_code'),
					'diet_classes' => DietClass::where('diet_code',$diet_code)->orderBy('class_position')->get(),
					'diet_code' => $diet_code,
					'class_code' => $class_code, 
					'weekOfMonth' => $weekOfMonth,
					'dayOfWeek' => $dayOfWeek,
					'weeks' => $this->weeks,
					'days' => $this->days,
					'diet_periods' => DietPeriod::all()->sortBy('period_position'),
					'dietHelper' => new DietHelper(),
			]);
	}


	public function cooklist(Request $request)
	{
			
			$dt = Carbon::now();
			if (empty($request->dayOfWeek)) {
					$dayOfWeek = $dt->dayOfWeek;
			} else {
					$dayOfWeek = $request->dayOfWeek;
			}
			if (empty($request->weekOfMonth)) {
					$weekOfMonth = $dt->weekOfMonth;
			} else {
					$weekOfMonth = $request->weekOfMonth;
			}

			if ($weekOfMonth>4) $weekOfMonth=1;

			$diet_code = "normal";
			$class_code = "class1";

			if (!empty($request->diet_code)) $diet_code = $request->diet_code;
			if (!empty($request->class_code)) $class_code = $request->class_code;
					
			$menu_products = DB::table('diet_menus as a')
							->select('a.product_code','a.period_code','c.period_position', 'product_name','period_name','a.class_code')
							->leftjoin('diet_classes as b', 'b.class_code','=','a.class_code')
							->leftjoin('diet_periods as c', 'c.period_code','=','a.period_code')
							->leftjoin('products as d', 'd.product_code','=','a.product_code')
							->where('b.diet_code','=', $diet_code)
							->where('week_index','=', $weekOfMonth)
							->where('day_index','=', $dayOfWeek)
							->groupBy('a.product_code')
							->orderBy('period_position')
							->orderBy('product_name')
							->get();


			return view('diet_menus.cooklist', [
					'diets' => Diet::all()->sortBy('diet_name')->lists('diet_name', 'diet_code'),
					'diet_classes' => DietClass::where('diet_code',$diet_code)->orderBy('class_position')->get(),
					'diet_code' => $diet_code,
					'class_code' => $class_code, 
					'weekOfMonth' => $weekOfMonth,
					'dayOfWeek' => $dayOfWeek,
					'weeks' => $this->weeks,
					'days' => $this->days,
					'diet_periods' => DietPeriod::all()->sortBy('period_position'),
					'dietHelper' => new DietHelper(),
					'menu_products' => $menu_products,
			]);
	}

	public function bom(Request $request)
	{
			
			$dt = Carbon::now();
			if (empty($request->dayOfWeek)) {
					$dayOfWeek = $dt->dayOfWeek;
			} else {
					$dayOfWeek = $request->dayOfWeek;
			}
			if (empty($request->weekOfMonth)) {
					$weekOfMonth = $dt->weekOfMonth;
			} else {
					$weekOfMonth = $request->weekOfMonth;
			}

			if ($weekOfMonth>4) $weekOfMonth=1;

			$diet_code = "normal";
			$class_code = "class1";

			if (!empty($request->diet_code)) $diet_code = $request->diet_code;
			if (!empty($request->class_Code)) $class_code = $request->class_code;
					
			$menu_products = DB::table('diet_menus as a')
							->select('a.product_code','a.period_code','c.period_position', 'product_name','period_name','a.class_code')
							->leftjoin('diet_classes as b', 'b.class_code','=','a.class_code')
							->leftjoin('diet_periods as c', 'c.period_code','=','a.period_code')
							->leftjoin('products as d', 'd.product_code','=','a.product_code')
							->where('b.diet_code','=', $diet_code)
							->where('week_index','=', $weekOfMonth)
							->where('day_index','=', $dayOfWeek)
							->where('product_bom', '=', 1)
							->groupBy('a.product_code')
							->orderBy('period_position')
							->orderBy('product_name')
							->get();

			return view('diet_menus.bom', [
					'diets' => Diet::all()->sortBy('diet_name')->lists('diet_name', 'diet_code'),
					'diet_classes' => DietClass::where('diet_code',$diet_code)->orderBy('class_position')->get(),
					'diet_code' => $diet_code,
					'class_code' => $class_code, 
					'weekOfMonth' => $weekOfMonth,
					'dayOfWeek' => $dayOfWeek,
					'weeks' => $this->weeks,
					'days' => $this->days,
					'diet_periods' => DietPeriod::all()->sortBy('period_position'),
					'dietHelper' => new DietHelper(),
					'menu_products' => $menu_products,
			]);
	}

	public function workorder(Request $request)
	{
			$dt = Carbon::now();
			if (empty($request->dayOfWeek)) {
					$dayOfWeek = $dt->dayOfWeek;
			} else {
					$dayOfWeek = $request->dayOfWeek;
			}
			if (empty($request->weekOfMonth)) {
					$weekOfMonth = $dt->weekOfMonth;
			} else {
					$weekOfMonth = $request->weekOfMonth;
			}

			if ($weekOfMonth>4) $weekOfMonth=1;

			$diet_code = "normal";
			$period_code = "breakfast";

			if (!empty($request->diet_code)) $diet_code = $request->diet_code;
			if (!empty($request->period_code)) $period_code = $request->period_code;


			$menu_products = DB::table('diet_menus as a')
							->select('a.product_code','a.period_code','c.period_position', 'product_name','period_name','a.class_code')
							->leftjoin('diet_classes as b', 'b.class_code','=','a.class_code')
							->leftjoin('diet_periods as c', 'c.period_code','=','a.period_code')
							->leftjoin('products as d', 'd.product_code','=','a.product_code')
							->where('b.diet_code','=', $diet_code)
							->where('week_index','=', $weekOfMonth)
							->where('day_index','=', $dayOfWeek)
							->where('a.period_code','=', $period_code)
							->groupBy('a.product_code')
							->orderBy('period_position')
							->orderBy('product_name')
							->get();

			$wards = DietHelper::occupiedWards();

			return view('diet_menus.workorder', [
					'diets' => Diet::all()->sortBy('diet_name')->lists('diet_name', 'diet_code'),
					'diet_classes' => DietClass::where('diet_code',$diet_code)->orderBy('class_position')->get(),
					'diet_periods' => DietPeriod::all()->sortBy('period_position')->lists('period_name', 'period_code'),
					'diet_code' => $diet_code,
					'weekOfMonth' => $weekOfMonth,
					'dayOfWeek' => $dayOfWeek,
					'weeks' => $this->weeks,
					'days' => $this->days,
					'dietHelper' => new DietHelper(),
					'menu_products' => $menu_products,
					'wards' => $wards,
					'grand_total'=>0,
					'total'=>0,
					'period_code'=>$period_code,
			]);
	}


	public function distribution(Request $request)
	{
			
			$diet_code = "normal";
			$class_code = "class1";
			$period_code = "breakfast";
			$dt = Carbon::now();

			if (empty($request->dayOfWeek)) {
					$dayOfWeek = $dt->dayOfWeek;
			} else {
					$dayOfWeek = $request->dayOfWeek;
			}
			if (empty($request->weekOfMonth)) {
					$weekOfMonth = $dt->weekOfMonth;
			} else {
					$weekOfMonth = $request->weekOfMonth;
			}

			if ($weekOfMonth>4) $weekOfMonth=1;

			if (!empty($request->class_code)) $class_code = $request->class_code;
			if (!empty($request->diet_code)) $diet_code = $request->diet_code;
			if (!empty($request->period_code)) $period_code = $request->period_code;

			$menu_products = DB::table('diet_menus as a')
							->select('a.product_code','a.period_code','c.period_position', 'product_name','period_name','a.class_code')
							->leftjoin('diet_classes as b', 'b.class_code','=','a.class_code')
							->leftjoin('diet_periods as c', 'c.period_code','=','a.period_code')
							->leftjoin('products as d', 'd.product_code','=','a.product_code')
							->where('b.diet_code','=', $diet_code)
							->where('week_index','=', $weekOfMonth)
							->where('day_index','=', $dayOfWeek)
							->where('a.period_code','=', $period_code)
							->where('a.class_code','=', $class_code)
							->groupBy('a.product_code')
							->orderBy('period_position')
							->orderBy('product_name')
							->get();

			return view('diet_menus.distribution', [
					'diets' => Diet::all()->sortBy('diet_name')->lists('diet_name', 'diet_code'),
					'diet_classes' => DietClass::where('diet_code', $diet_code)->orderBy('class_name')->lists('class_name', 'class_code'),
					'diet_code' => $diet_code,
					'class_code' => $class_code, 
					'weekOfMonth' => $weekOfMonth,
					'dayOfWeek' => $dayOfWeek,
					'weeks' => $this->weeks,
					'days' => $this->days,
					'diet_periods' => DietPeriod::all()->sortBy('period_position')->lists('period_name', 'period_code'),
					'dietHelper' => new DietHelper(),
					'menu_products' => $menu_products,
					'wards' => Ward::all(),
					'period_code'=>$period_code,
			]);
	}

	public function order(Request $request)
	{
			$diet_code = "normal";
			
			if (!empty($request->diet_code)) {
					$diet_code = $request->diet_code;
			}

			return view('diet_menus.order', [
					'diets' => Diet::all()->sortBy('diet_name')->lists('diet_name', 'diet_code'),
					'diet_classes' => DietClass::where('diet_code',$diet_code)->orderBy('class_position')->get(),
					'diet_code' => $diet_code,
					'dietHelper' => new DietHelper(),
					'wards' => DietHelper::occupiedWards(),
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


	public function delete($id)
	{
		$menu = DietMenu::findOrFail($id);

		return view('diet_menus.destroy', [
			'menu'=>$menu
			]);

	}

	public function destroy($id)
	{	
			$menu = DietMenu::findOrFail($id);
			DietMenu::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/diet_menus/menu/'.$menu->class_code.'/'.$menu->period_code.'/'.$menu->week_index.'/'.$menu->day_index);
	}
	
}
