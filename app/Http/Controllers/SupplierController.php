<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Supplier;
use Log;
use DB;
use Session;
use App\State;
use App\Nation;

class SupplierController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$suppliers = DB::table('suppliers')
					->orderBy('supplier_name')
					->paginate($this->paginateValue);
			return view('suppliers.index', [
					'suppliers'=>$suppliers
			]);
	}

	public function create()
	{
			$supplier = new Supplier();
			return view('suppliers.create', [
					'supplier' => $supplier,
					'state' => State::all()->sortBy('state_name')->lists('state_name', 'state_code')->prepend('',''),
					'nation' => Nation::all()->sortBy('nation_name')->lists('nation_name', 'nation_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$supplier = new Supplier();
			$valid = $supplier->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$supplier = new Supplier($request->all());
					$supplier->supplier_code = $request->supplier_code;
					$supplier->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/suppliers/id/'.$supplier->supplier_code);
			} else {
					return redirect('/suppliers/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$supplier = Supplier::findOrFail($id);
			return view('suppliers.edit', [
					'supplier'=>$supplier,
					'state' => State::all()->sortBy('state_name')->lists('state_name', 'state_code')->prepend('',''),
					'nation' => Nation::all()->sortBy('nation_name')->lists('nation_name', 'nation_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$supplier = Supplier::findOrFail($id);
			$supplier->fill($request->input());


			$valid = $supplier->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$supplier->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/suppliers/id/'.$id);
			} else {
					return view('suppliers.edit', [
							'supplier'=>$supplier,
					])
					->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$supplier = Supplier::findOrFail($id);
		return view('suppliers.destroy', [
			'supplier'=>$supplier
			]);

	}
	public function destroy($id)
	{	
			Supplier::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/suppliers');
	}
	
	public function search(Request $request)
	{
			$suppliers = DB::table('suppliers')
					->where('supplier_name','like','%'.$request->search.'%')
					->orWhere('supplier_code', 'like','%'.$request->search.'%')
					->orderBy('supplier_name')
					->paginate($this->paginateValue);

			return view('suppliers.index', [
					'suppliers'=>$suppliers,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$suppliers = DB::table('suppliers')
					->where('supplier_code','=',$id)
					->paginate($this->paginateValue);

			return view('suppliers.index', [
					'suppliers'=>$suppliers
			]);
	}
}
