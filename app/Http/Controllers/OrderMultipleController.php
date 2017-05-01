<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\OrderMultiple;
use Log;
use DB;
use Session;


class OrderMultipleController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$order_multiples = DB::table('order_multiples')
					->orderBy('order_id')
					->paginate($this->paginateValue);
			return view('order_multiples.index', [
					'order_multiples'=>$order_multiples
			]);
	}

	public function create()
	{
			$order_multiple = new OrderMultiple();
			return view('order_multiples.create', [
					'order_multiple' => $order_multiple,
				
					]);
	}

	public function store(Request $request) 
	{
			$order_multiple = new OrderMultiple();
			$valid = $order_multiple->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$order_multiple = new OrderMultiple($request->all());
					$order_multiple->multiple_id = $request->multiple_id;
					$order_multiple->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/order_multiples/id/'.$order_multiple->multiple_id);
			} else {
					return redirect('/order_multiples/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$order_multiple = OrderMultiple::findOrFail($id);
			return view('order_multiples.edit', [
					'order_multiple'=>$order_multiple,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$order_multiple = OrderMultiple::findOrFail($id);
			$order_multiple->fill($request->input());

			$order_multiple->order_completed = $request->order_completed ?: 0;

			$valid = $order_multiple->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$order_multiple->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/order_multiples/id/'.$id);
			} else {
					return view('order_multiples.edit', [
							'order_multiple'=>$order_multiple,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$order_multiple = OrderMultiple::findOrFail($id);
		return view('order_multiples.destroy', [
			'order_multiple'=>$order_multiple
			]);

	}
	public function destroy($id)
	{	
			OrderMultiple::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/order_multiples');
	}
	
	public function search(Request $request)
	{
			$order_multiples = DB::table('order_multiples')
					->where('order_id','like','%'.$request->search.'%')
					->orWhere('multiple_id', 'like','%'.$request->search.'%')
					->orderBy('order_id')
					->paginate($this->paginateValue);

			return view('order_multiples.index', [
					'order_multiples'=>$order_multiples,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$order_multiples = DB::table('order_multiples')
					->where('multiple_id','=',$id)
					->paginate($this->paginateValue);

			return view('order_multiples.index', [
					'order_multiples'=>$order_multiples
			]);
	}
}
