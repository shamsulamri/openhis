<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\ProductGroup;
use Log;
use DB;
use Session;
use App\GeneralLedger;


class ProductGroupController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$product_groups = DB::table('product_groups')
					->orderBy('group_name')
					->paginate($this->paginateValue);
			return view('product_groups.index', [
					'product_groups'=>$product_groups
			]);
	}

	public function create()
	{
			$product_group = new ProductGroup();
			return view('product_groups.create', [
					'product_group' => $product_group,
					'gl' => GeneralLedger::all()->sortBy('gl_name')->lists('gl_name', 'gl_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$product_group = new ProductGroup();
			$valid = $product_group->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$product_group = new ProductGroup($request->all());
					$product_group->group_code = $request->group_code;
					$product_group->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/product_groups/id/'.$product_group->group_code);
			} else {
					return redirect('/product_groups/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$product_group = ProductGroup::findOrFail($id);
			return view('product_groups.edit', [
					'product_group'=>$product_group,
					'gl' => GeneralLedger::all()->sortBy('gl_name')->lists('gl_name', 'gl_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$product_group = ProductGroup::findOrFail($id);
			$product_group->fill($request->input());


			$valid = $product_group->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$product_group->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/product_groups/id/'.$id);
			} else {
					return view('product_groups.edit', [
							'product_group'=>$product_group,
							'gl' => GeneralLedger::all()->sortBy('gl_name')->lists('gl_name', 'gl_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$product_group = ProductGroup::findOrFail($id);
		return view('product_groups.destroy', [
			'product_group'=>$product_group
			]);

	}
	public function destroy($id)
	{	
			ProductGroup::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/product_groups');
	}
	
	public function search(Request $request)
	{
			$product_groups = DB::table('product_groups')
					->where('group_name','like','%'.$request->search.'%')
					->orWhere('group_code', 'like','%'.$request->search.'%')
					->orderBy('group_name')
					->paginate($this->paginateValue);

			return view('product_groups.index', [
					'product_groups'=>$product_groups,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$product_groups = DB::table('product_groups')
					->where('group_code','=',$id)
					->paginate($this->paginateValue);

			return view('product_groups.index', [
					'product_groups'=>$product_groups
			]);
	}
}
