<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\ProductCategory;
use Log;
use DB;
use Session;
use App\QueueLocation as Location;


class ProductCategoryController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$product_categories = DB::table('product_categories')
					->orderBy('category_name')
					->paginate($this->paginateValue);
			return view('product_categories.index', [
					'product_categories'=>$product_categories
			]);
	}

	public function create()
	{
			$product_category = new ProductCategory();
			return view('product_categories.create', [
					'product_category' => $product_category,
					'location' => Location::all()->sortBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$product_category = new ProductCategory();
			$valid = $product_category->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$product_category = new ProductCategory($request->all());
					$product_category->category_code = $request->category_code;
					$product_category->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/product_categories/id/'.$product_category->category_code);
			} else {
					return redirect('/product_categories/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$product_category = ProductCategory::findOrFail($id);
			return view('product_categories.edit', [
					'product_category'=>$product_category,
					'location' => Location::all()->sortBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$product_category = ProductCategory::findOrFail($id);
			$product_category->fill($request->input());

			$valid = $product_category->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$product_category->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/product_categories/id/'.$id);
			} else {
					return view('product_categories.edit', [
							'product_category'=>$product_category,
					'location' => Location::all()->sortBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$product_category = ProductCategory::findOrFail($id);
		return view('product_categories.destroy', [
			'product_category'=>$product_category
			]);

	}
	public function destroy($id)
	{	
			ProductCategory::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/product_categories');
	}
	
	public function search(Request $request)
	{
			$product_categories = DB::table('product_categories')
					->where('category_name','like','%'.$request->search.'%')
					->orWhere('category_code', 'like','%'.$request->search.'%')
					->orderBy('category_name')
					->paginate($this->paginateValue);

			return view('product_categories.index', [
					'product_categories'=>$product_categories,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$product_categories = DB::table('product_categories')
					->where('category_code','=',$id)
					->paginate($this->paginateValue);

			return view('product_categories.index', [
					'product_categories'=>$product_categories
			]);
	}
}
