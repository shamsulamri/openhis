<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\PaymentMethod;
use Log;
use DB;
use Session;


class PaymentMethodController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$payment_methods = DB::table('payment_methods')
					->orderBy('payment_name')
					->paginate($this->paginateValue);
			return view('payment_methods.index', [
					'payment_methods'=>$payment_methods
			]);
	}

	public function create()
	{
			$payment_method = new PaymentMethod();
			return view('payment_methods.create', [
					'payment_method' => $payment_method,
				
					]);
	}

	public function store(Request $request) 
	{
			$payment_method = new PaymentMethod();
			$valid = $payment_method->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$payment_method = new PaymentMethod($request->all());
					$payment_method->payment_code = $request->payment_code;
					$payment_method->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/payment_methods/id/'.$payment_method->payment_code);
			} else {
					return redirect('/payment_methods/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$payment_method = PaymentMethod::findOrFail($id);
			return view('payment_methods.edit', [
					'payment_method'=>$payment_method,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$payment_method = PaymentMethod::findOrFail($id);
			$payment_method->fill($request->input());


			$valid = $payment_method->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$payment_method->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/payment_methods/id/'.$id);
			} else {
					return view('payment_methods.edit', [
							'payment_method'=>$payment_method,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$payment_method = PaymentMethod::findOrFail($id);
		return view('payment_methods.destroy', [
			'payment_method'=>$payment_method
			]);

	}
	public function destroy($id)
	{	
			PaymentMethod::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/payment_methods');
	}
	
	public function search(Request $request)
	{
			$payment_methods = DB::table('payment_methods')
					->where('payment_name','like','%'.$request->search.'%')
					->orWhere('payment_code', 'like','%'.$request->search.'%')
					->orderBy('payment_name')
					->paginate($this->paginateValue);

			return view('payment_methods.index', [
					'payment_methods'=>$payment_methods,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$payment_methods = DB::table('payment_methods')
					->where('payment_code','=',$id)
					->paginate($this->paginateValue);

			return view('payment_methods.index', [
					'payment_methods'=>$payment_methods
			]);
	}
}
