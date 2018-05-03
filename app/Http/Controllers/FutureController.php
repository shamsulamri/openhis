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
use App\OrderInvestigation;

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
					->where('order_completed', '=', 0)
					->where('order_is_future','=',1)
					->orderBy('investigation_date');

			$futures = $futures->paginate($this->paginateValue);
			return view('futures.index', [
					'futures'=>$futures
			]);
	}

	public function search(Request $request)
	{
			$futures = Order::orderBy('orders.order_id')
					->leftjoin('order_investigations as a', 'orders.order_id','=','a.order_id')
					->leftjoin('encounters as b', 'orders.encounter_id','=','b.encounter_id')
					->leftjoin('patients as c', 'c.patient_id','=','b.patient_id')
					->where('order_completed', '=', 0)
					->where('order_is_future','=',1)
					->where(function ($query) use ($request) {
							$query->where('patient_name','like','%'.$request->search.'%')
									->orWhere('patient_mrn', 'like','%'.$request->search.'%')
									->orWhere('patient_new_ic', 'like','%'.$request->search.'%');
					})
					->orderBy('investigation_date')
					->paginate($this->paginateValue);

			return view('futures.index', [
					'futures'=>$futures,
					'search'=>$request->search
					]);
	}

}
