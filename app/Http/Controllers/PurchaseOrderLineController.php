<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\PurchaseOrderLine;
use Log;
use DB;
use Session;
use App\Product;


class PurchaseOrderLineController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$purchase_order_lines = DB::table('purchase_order_lines')
					->orderBy('purchase_id')
					->paginate($this->paginateValue);
			return view('purchase_order_lines.index', [
					'purchase_order_lines'=>$purchase_order_lines
			]);
	}

	public function create()
	{
			$purchase_order_line = new PurchaseOrderLine();
			return view('purchase_order_lines.create', [
					'purchase_order_line' => $purchase_order_line,
					'product' => Product::all()->sortBy('product_name')->lists('product_name', 'product_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$purchase_order_line = new PurchaseOrderLine();
			$valid = $purchase_order_line->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$purchase_order_line = new PurchaseOrderLine($request->all());
					$purchase_order_line->line_id = $request->line_id;
					$purchase_order_line->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/purchase_order_lines/id/'.$purchase_order_line->line_id);
			} else {
					return redirect('/purchase_order_lines/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$purchase_order_line = PurchaseOrderLine::findOrFail($id);
			return view('purchase_order_lines.edit', [
					'purchase_order_line'=>$purchase_order_line,
					'product' => Product::all()->sortBy('product_name')->lists('product_name', 'product_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$purchase_order_line = PurchaseOrderLine::findOrFail($id);
			$purchase_order_line->fill($request->input());


			$valid = $purchase_order_line->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$purchase_order_line->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/purchase_order_lines/id/'.$id);
			} else {
					return view('purchase_order_lines.edit', [
							'purchase_order_line'=>$purchase_order_line,
					'product' => Product::all()->sortBy('product_name')->lists('product_name', 'product_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$purchase_order_line = PurchaseOrderLine::findOrFail($id);
		return view('purchase_order_lines.destroy', [
			'purchase_order_line'=>$purchase_order_line
			]);

	}
	public function destroy($id)
	{	
			PurchaseOrderLine::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/purchase_order_lines');
	}
	
	public function search(Request $request)
	{
			$purchase_order_lines = DB::table('purchase_order_lines')
					->where('purchase_id','like','%'.$request->search.'%')
					->orWhere('line_id', 'like','%'.$request->search.'%')
					->orderBy('purchase_id')
					->paginate($this->paginateValue);

			return view('purchase_order_lines.index', [
					'purchase_order_lines'=>$purchase_order_lines,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$purchase_order_lines = DB::table('purchase_order_lines')
					->where('line_id','=',$id)
					->paginate($this->paginateValue);

			return view('purchase_order_lines.index', [
					'purchase_order_lines'=>$purchase_order_lines
			]);
	}
}
