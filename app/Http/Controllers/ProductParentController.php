<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\ProductCategoryParent;
use Log;
use DB;
use Session;


class ProductParentController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$product_category_parents = ProductCategoryParent::orderBy('parent_code')
					->paginate($this->paginateValue);

			return view('product_category_parents.index', [
					'product_category_parents'=>$product_category_parents
			]);
	}

	public function create()
	{
			$product_category_parent = new ProductCategoryParent();
			return view('product_category_parents.create', [
					'product_category_parent' => $product_category_parent,
				
					]);
	}

	public function store(Request $request) 
	{
			$product_category_parent = new ProductCategoryParent();
			$valid = $product_category_parent->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$product_category_parent = new ProductCategoryParent($request->all());
					$product_category_parent->parent_code = $request->parent_code;
					$product_category_parent->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/product_category_parents/id/'.$product_category_parent->parent_code);
			} else {
					return redirect('/product_category_parents/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$product_category_parent = ProductCategoryParent::findOrFail($id);
			return view('product_category_parents.edit', [
					'product_category_parent'=>$product_category_parent,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$product_category_parent = ProductCategoryParent::findOrFail($id);
			$product_category_parent->fill($request->input());


			$valid = $product_category_parent->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$product_category_parent->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/product_category_parents/id/'.$id);
			} else {
					return view('product_category_parents.edit', [
							'product_category_parent'=>$product_category_parent,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$product_category_parent = ProductCategoryParent::findOrFail($id);
		return view('product_category_parents.destroy', [
			'product_category_parent'=>$product_category_parent
			]);

	}
	public function destroy($id)
	{	
			ProductCategoryParent::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/product_category_parents');
	}
	
	public function search(Request $request)
	{
			$product_category_parents = ProductCategoryParent::where('parent_code','like','%'.$request->search.'%')
					->orWhere('parent_code', 'like','%'.$request->search.'%')
					->orderBy('parent_code')
					->paginate($this->paginateValue);

			return view('product_category_parents.index', [
					'product_category_parents'=>$product_category_parents,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$product_category_parents = ProductCategoryParent::where('parent_code','=',$id)
					->paginate($this->paginateValue);

			return view('product_category_parents.index', [
					'product_category_parents'=>$product_category_parents
			]);
	}
}
