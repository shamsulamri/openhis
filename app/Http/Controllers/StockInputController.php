<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\StockInput;
use Log;
use DB;
use Session;
use App\Product;


class StockInputController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$stock_inputs = DB::table('stock_inputs')
					->orderBy('input_id')
					->paginate($this->paginateValue);
			return view('stock_inputs.index', [
					'stock_inputs'=>$stock_inputs
			]);
	}

	public function create()
	{
			$stock_input = new StockInput();
			return view('stock_inputs.create', [
					'stock_input' => $stock_input,
					'product' => Product::all()->sortBy('product_name')->lists('product_name', 'product_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$stock_input = new StockInput();
			$valid = $stock_input->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$stock_input = new StockInput($request->all());
					$stock_input->input_id = $request->input_id;
					$stock_input->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/stock_inputs/id/'.$stock_input->input_id);
			} else {
					return redirect('/stock_inputs/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$stock_input = StockInput::findOrFail($id);
			return view('stock_inputs.edit', [
					'stock_input'=>$stock_input,
					'product' => Product::all()->sortBy('product_name')->lists('product_name', 'product_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$stock_input = StockInput::findOrFail($id);
			$stock_input->fill($request->input());


			$valid = $stock_input->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$stock_input->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/stock_inputs/id/'.$id);
			} else {
					return view('stock_inputs.edit', [
							'stock_input'=>$stock_input,
					'product' => Product::all()->sortBy('product_name')->lists('product_name', 'product_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$stock_input = StockInput::findOrFail($id);
		return view('stock_inputs.destroy', [
			'stock_input'=>$stock_input
			]);

	}
	public function destroy($id)
	{	
			StockInput::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/stock_inputs');
	}
	
	public function search(Request $request)
	{
			$stock_inputs = DB::table('stock_inputs')
					->where('input_id','like','%'.$request->search.'%')
					->orWhere('input_id', 'like','%'.$request->search.'%')
					->orderBy('input_id')
					->paginate($this->paginateValue);

			return view('stock_inputs.index', [
					'stock_inputs'=>$stock_inputs,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$stock_inputs = DB::table('stock_inputs')
					->where('input_id','=',$id)
					->paginate($this->paginateValue);

			return view('stock_inputs.index', [
					'stock_inputs'=>$stock_inputs
			]);
	}


	public function bulkIndex()
	{

	}

}
