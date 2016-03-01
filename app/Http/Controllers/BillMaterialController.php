<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\BillMaterial;
use Log;
use DB;
use Session;
use App\Product;

class BillMaterialController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$bill_materials = DB::table('bill_materials')
					->orderBy('product_code')
					->paginate($this->paginateValue);
			return view('bill_materials.index', [
					'bill_materials'=>$bill_materials
			]);
	}

	public function create()
	{
			$bill_material = new BillMaterial();
			return view('bill_materials.create', [
					'bill_material' => $bill_material,
					]);
	}

	public function store(Request $request) 
	{
			$bill_material = new BillMaterial();
			$valid = $bill_material->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$bill_material = new BillMaterial($request->all());
					$bill_material->id = $request->id;
					$bill_material->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/bill_materials/id/'.$bill_material->id);
			} else {
					return redirect('/bill_materials/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$bill_material = BillMaterial::findOrFail($id);
			return view('bill_materials.edit', [
					'bill_material'=>$bill_material,
					]);
	}

	public function update(Request $request, $id) 
	{
			$bill_material = BillMaterial::findOrFail($id);
			$bill_material->fill($request->input());


			$valid = $bill_material->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$bill_material->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/bill_materials/id/'.$id);
			} else {
					return view('bill_materials.edit', [
							'bill_material'=>$bill_material,
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$bill_material = BillMaterial::findOrFail($id);
		return view('bill_materials.destroy', [
			'bill_material'=>$bill_material
			]);

	}
	public function destroy($id)
	{	
			BillMaterial::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/bill_materials');
	}
	
	public function search(Request $request)
	{
			$bill_materials = DB::table('bill_materials')
					->where('product_code','like','%'.$request->search.'%')
					->orWhere('id', 'like','%'.$request->search.'%')
					->orderBy('product_code')
					->paginate($this->paginateValue);

			return view('bill_materials.index', [
					'bill_materials'=>$bill_materials,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$bill_materials = DB::table('bill_materials')
					->where('id','=',$id)
					->paginate($this->paginateValue);

			return view('bill_materials.index', [
					'bill_materials'=>$bill_materials
			]);
	}
}
