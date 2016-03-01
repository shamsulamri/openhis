<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\OrderForm;
use Log;
use DB;
use Session;


class OrderFormController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$order_forms = DB::table('order_forms')
					->orderBy('form_name')
					->paginate($this->paginateValue);
			return view('order_forms.index', [
					'order_forms'=>$order_forms
			]);
	}

	public function create()
	{
			$order_form = new OrderForm();
			return view('order_forms.create', [
					'order_form' => $order_form,
				
					]);
	}

	public function store(Request $request) 
	{
			$order_form = new OrderForm();
			$valid = $order_form->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$order_form = new OrderForm($request->all());
					$order_form->form_code = $request->form_code;
					$order_form->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/order_forms/id/'.$order_form->form_code);
			} else {
					return redirect('/order_forms/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$order_form = OrderForm::findOrFail($id);
			return view('order_forms.edit', [
					'order_form'=>$order_form,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$order_form = OrderForm::findOrFail($id);
			$order_form->fill($request->input());


			$valid = $order_form->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$order_form->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/order_forms/id/'.$id);
			} else {
					return view('order_forms.edit', [
							'order_form'=>$order_form,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$order_form = OrderForm::findOrFail($id);
		return view('order_forms.destroy', [
			'order_form'=>$order_form
			]);

	}
	public function destroy($id)
	{	
			OrderForm::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/order_forms');
	}
	
	public function search(Request $request)
	{
			$order_forms = DB::table('order_forms')
					->where('form_name','like','%'.$request->search.'%')
					->orWhere('form_code', 'like','%'.$request->search.'%')
					->orderBy('form_name')
					->paginate($this->paginateValue);

			return view('order_forms.index', [
					'order_forms'=>$order_forms,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$order_forms = DB::table('order_forms')
					->where('form_code','=',$id)
					->paginate($this->paginateValue);

			return view('order_forms.index', [
					'order_forms'=>$order_forms
			]);
	}
}
