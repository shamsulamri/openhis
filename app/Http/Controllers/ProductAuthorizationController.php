<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\ProductAuthorization;
use Log;
use DB;
use Session;
use App\ProductCategory as Category;


class ProductAuthorizationController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$product_authorizations = DB::table('product_authorizations')
					->orderBy('author_id')
					->paginate($this->paginateValue);
			return view('product_authorizations.index', [
					'product_authorizations'=>$product_authorizations
			]);
	}

	public function create()
	{
			$product_authorization = new ProductAuthorization();
			return view('product_authorizations.create', [
					'product_authorization' => $product_authorization,
					'category' => Category::all()->sortBy('category_name')->lists('category_name', 'category_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$product_authorization = new ProductAuthorization();
			$valid = $product_authorization->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$product_authorization = new ProductAuthorization($request->all());
					$product_authorization->id = $request->id;
					$product_authorization->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/product_authorizations/id/'.$product_authorization->id);
			} else {
					return redirect('/product_authorizations/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$product_authorization = ProductAuthorization::findOrFail($id);
			return view('product_authorizations.edit', [
					'product_authorization'=>$product_authorization,
					'category' => Category::all()->sortBy('category_name')->lists('category_name', 'category_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$product_authorization = ProductAuthorization::findOrFail($id);
			$product_authorization->fill($request->input());


			$valid = $product_authorization->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$product_authorization->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/product_authorizations/id/'.$id);
			} else {
					return view('product_authorizations.edit', [
							'product_authorization'=>$product_authorization,
					'category' => Category::all()->sortBy('category_name')->lists('category_name', 'category_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$product_authorization = ProductAuthorization::findOrFail($id);
		return view('product_authorizations.destroy', [
			'product_authorization'=>$product_authorization
			]);

	}
	public function destroy($id)
	{	
			ProductAuthorization::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/product_authorizations');
	}
	
	public function search(Request $request)
	{
			$product_authorizations = DB::table('product_authorizations')
					->where('author_id','like','%'.$request->search.'%')
					->orWhere('id', 'like','%'.$request->search.'%')
					->orderBy('author_id')
					->paginate($this->paginateValue);

			return view('product_authorizations.index', [
					'product_authorizations'=>$product_authorizations,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$product_authorizations = DB::table('product_authorizations')
					->where('id','=',$id)
					->paginate($this->paginateValue);

			return view('product_authorizations.index', [
					'product_authorizations'=>$product_authorizations
			]);
	}
}
