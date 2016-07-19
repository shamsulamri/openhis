<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\ProductMaintenance;
use Log;
use DB;
use Session;
use App\Product;
use App\MaintenanceReason as Reason;
use Carbon\Carbon;
use Auth;

class ProductMaintenanceController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function show($id) {
			$product = Product::find($id);
			$product_maintenances = DB::table('product_maintenances as a')
					->leftjoin('ref_maintenance_reasons as b','a.reason_code','=', 'b.reason_code')
					->where('product_code',$id)
					->paginate($this->paginateValue);
			return view('product_maintenances.index', [
					'product_maintenances'=>$product_maintenances,
					'product'=>$product,
			]);
	} 

	public function index()
	{
			$product_maintenances = DB::table('product_maintenances')
					->orderBy('reason_code')
					->paginate($this->paginateValue);
			return view('product_maintenances.index', [
					'product_maintenances'=>$product_maintenances
			]);
	}

	public function create(Request $request)
	{
			$product = Product::find($request->product_code);
			$product_maintenance = new ProductMaintenance();
			$today = date('d/m/Y H:i', strtotime(Carbon::now())); 
			$product_maintenance->maintain_datetime = date('d/m/Y H:i', strtotime(Carbon::now())); 

			return view('product_maintenances.create', [
					'product_maintenance' => $product_maintenance,
					'product' => $product, 
					'reason' => Reason::all()->sortBy('reason_name')->lists('reason_name', 'reason_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$product_maintenance = new ProductMaintenance();
			$valid = $product_maintenance->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$product_maintenance = new ProductMaintenance($request->all());
					$product_maintenance->user_id = Auth::user()->id;
					$product_maintenance->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/product_maintenances/'.$product_maintenance->product_code);
			} else {
					return redirect('/product_maintenances/create?product_code='.$request->product_code)
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$product_maintenance = ProductMaintenance::findOrFail($id);

			return view('product_maintenances.edit', [
					'product_maintenance'=>$product_maintenance,
					'product'=>Product::find($product_maintenance->product_code),
					'reason' => Reason::all()->sortBy('reason_name')->lists('reason_name', 'reason_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$product_maintenance = ProductMaintenance::findOrFail($id);
			$product_maintenance->fill($request->input());


			$valid = $product_maintenance->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$product_maintenance->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/product_maintenances/'.$product_maintenance->product_code);
			} else {
					return view('product_maintenances.edit', [
							'product_maintenance'=>$product_maintenance,
							'product'=>Product::find($product_maintenance->product_code),
							'reason' => Reason::all()->sortBy('reason_name')->lists('reason_name', 'reason_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$product_maintenance = ProductMaintenance::findOrFail($id);
		return view('product_maintenances.destroy', [
			'product_maintenance'=>$product_maintenance,
			'product'=>Product::find($product_maintenance->product_code),
			]);

	}
	public function destroy($id)
	{	
			$product_maintenance = ProductMaintenance::findOrFail($id);
			ProductMaintenance::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/product_maintenances/'.$product_maintenance->product_code);
	}
	
	public function search(Request $request)
	{
			$product_maintenances = DB::table('product_maintenances')
					->where('reason_code','like','%'.$request->search.'%')
					->orWhere('maintain_id', 'like','%'.$request->search.'%')
					->orderBy('reason_code')
					->paginate($this->paginateValue);

			return view('product_maintenances.index', [
					'product_maintenances'=>$product_maintenances,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$product_maintenances = DB::table('product_maintenances')
					->where('maintain_id','=',$id)
					->paginate($this->paginateValue);

			return view('product_maintenances.index', [
					'product_maintenances'=>$product_maintenances
			]);
	}
}
