<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\ProductStatus;
use Log;
use DB;
use Session;


class ProductStatusController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$product_statuses = DB::table('ref_product_statuses')
					->orderBy('status_name')
					->paginate($this->paginateValue);
			return view('product_statuses.index', [
					'product_statuses'=>$product_statuses
			]);
	}

	public function create()
	{
			$product_status = new ProductStatus();
			return view('product_statuses.create', [
					'product_status' => $product_status,
				
					]);
	}

	public function store(Request $request) 
	{
			$product_status = new ProductStatus();
			$valid = $product_status->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$product_status = new ProductStatus($request->all());
					$product_status->status_code = $request->status_code;
					$product_status->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/product_statuses/id/'.$product_status->status_code);
			} else {
					return redirect('/product_statuses/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$product_status = ProductStatus::findOrFail($id);
			return view('product_statuses.edit', [
					'product_status'=>$product_status,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$product_status = ProductStatus::findOrFail($id);
			$product_status->fill($request->input());


			$valid = $product_status->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$product_status->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/product_statuses/id/'.$id);
			} else {
					return view('product_statuses.edit', [
							'product_status'=>$product_status,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$product_status = ProductStatus::findOrFail($id);
		return view('product_statuses.destroy', [
			'product_status'=>$product_status
			]);

	}
	public function destroy($id)
	{	
			ProductStatus::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/product_statuses');
	}
	
	public function search(Request $request)
	{
			$product_statuses = DB::table('ref_product_statuses')
					->where('status_name','like','%'.$request->search.'%')
					->orWhere('status_code', 'like','%'.$request->search.'%')
					->orderBy('status_name')
					->paginate($this->paginateValue);

			return view('product_statuses.index', [
					'product_statuses'=>$product_statuses,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$product_statuses = DB::table('ref_product_statuses')
					->where('status_code','=',$id)
					->paginate($this->paginateValue);

			return view('product_statuses.index', [
					'product_statuses'=>$product_statuses
			]);
	}
}
