<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Order;
use Log;
use DB;
use Session;
use Gate;
use App\DojoUtility;
use Carbon\Carbon;

class FutureController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$futures = Order::orderBy('orders.order_id')
					->leftjoin('order_investigations as a', 'orders.order_id','=','a.order_id')
					->where('investigation_date', '>=', Carbon::today())
					->where('order_completed', '=', 0)
					->where('order_is_discharge','=',1)
					->orderBy('investigation_date');

			$futures = $futures->paginate($this->paginateValue);
			return view('futures.index', [
					'futures'=>$futures
			]);
	}

	public function search(Request $request)
	{
			$futures = DB::table('orders')
					->where('race_name','like','%'.$request->search.'%')
					->orWhere('race_code', 'like','%'.$request->search.'%')
					->orderBy('race_name')
					->paginate($this->paginateValue);

			return view('futures.index', [
					'futures'=>$futures,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$futures = DB::table('orders')
					->where('race_code','=',$id)
					->paginate($this->paginateValue);

			return view('futures.index', [
					'futures'=>$futures
			]);
	}
}
