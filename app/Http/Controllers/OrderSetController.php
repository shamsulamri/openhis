<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\OrderSet;
use Log;
use DB;
use Session;
use App\Product;
use App\Set;

class OrderSetController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index($set_code)
	{
			$order_sets = DB::table('order_sets as a')
					->leftJoin('products as b','b.product_code', '=','a.product_code')
					->where('a.set_code','=',$set_code)
					->orderBy('product_name')
					->paginate($this->paginateValue);

			$set = Set::find($set_code);

			return view('order_sets.index', [
					'order_sets'=>$order_sets,
					'set'=>$set,
			]);
	}

	public function create()
	{
			$order_set = new OrderSet();
			return view('order_sets.create', [
					'order_set' => $order_set,
					'product' => Product::all()->sortBy('product_name')->lists('product_name', 'product_code')->prepend('',''),
					'set' => Set::all()->sortBy('set_name')->lists('set_name', 'set_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$order_set = new OrderSet();
			$valid = $order_set->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$order_set = new OrderSet($request->all());
					$order_set->set_code = $request->set_code;
					$order_set->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/order_sets/id/'.$order_set->set_code);
			} else {
					return redirect('/order_sets/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$order_set = OrderSet::findOrFail($id);
			return view('order_sets.edit', [
					'order_set'=>$order_set,
					'product' => Product::all()->sortBy('product_name')->lists('product_name', 'product_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$order_set = OrderSet::findOrFail($id);
			$order_set->fill($request->input());


			$valid = $order_set->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$order_set->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/order_sets/id/'.$id);
			} else {
					return view('order_sets.edit', [
							'order_set'=>$order_set,
					'product' => Product::all()->sortBy('product_name')->lists('product_name', 'product_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$order_set = OrderSet::findOrFail($id);
		return view('order_sets.destroy', [
			'order_set'=>$order_set
			]);

	}
	public function destroy($id)
	{	
			$order_set = OrderSet::findOrFail($id);
			OrderSet::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/order_sets/index/'.$order_set->set_code);
	}
	
	public function search(Request $request)
	{
			$order_sets = DB::table('order_sets')
					->where('product_code','like','%'.$request->search.'%')
					->orWhere('set_code', 'like','%'.$request->search.'%')
					->orderBy('product_code')
					->paginate($this->paginateValue);

			return view('order_sets.index', [
					'order_sets'=>$order_sets,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$order_sets = DB::table('order_sets')
					->where('set_code','=',$id)
					->paginate($this->paginateValue);

			return view('order_sets.index', [
					'order_sets'=>$order_sets
			]);
	}
}
