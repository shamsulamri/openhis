<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\StockInputLine;
use Log;
use DB;
use Session;
use App\Product;


class StockInputLineController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$stock_input_lines = DB::table('stock_input_lines')
					->orderBy('line_id')
					->paginate($this->paginateValue);
			return view('stock_input_lines.index', [
					'stock_input_lines'=>$stock_input_lines
			]);
	}

	public function create()
	{
			$stock_input_line = new StockInputLine();
			return view('stock_input_lines.create', [
					'stock_input_line' => $stock_input_line,
					'product' => Product::all()->sortBy('product_name')->lists('product_name', 'product_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$stock_input_line = new StockInputLine();
			$valid = $stock_input_line->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$stock_input_line = new StockInputLine($request->all());
					$stock_input_line->line_id = $request->line_id;
					$stock_input_line->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/stock_input_lines/id/'.$stock_input_line->line_id);
			} else {
					return redirect('/stock_input_lines/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$stock_input_line = StockInputLine::findOrFail($id);
			return view('stock_input_lines.edit', [
					'stock_input_line'=>$stock_input_line,
					'product' => Product::all()->sortBy('product_name')->lists('product_name', 'product_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$stock_input_line = StockInputLine::findOrFail($id);
			$stock_input_line->fill($request->input());


			$valid = $stock_input_line->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$stock_input_line->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/stock_input_lines/id/'.$id);
			} else {
					return view('stock_input_lines.edit', [
							'stock_input_line'=>$stock_input_line,
					'product' => Product::all()->sortBy('product_name')->lists('product_name', 'product_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$stock_input_line = StockInputLine::findOrFail($id);
		return view('stock_input_lines.destroy', [
			'stock_input_line'=>$stock_input_line
			]);

	}
	public function destroy($id)
	{	
			StockInputLine::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/stock_input_lines');
	}
	
	public function search(Request $request)
	{
			$stock_input_lines = DB::table('stock_input_lines')
					->where('line_id','like','%'.$request->search.'%')
					->orWhere('line_id', 'like','%'.$request->search.'%')
					->orderBy('line_id')
					->paginate($this->paginateValue);

			return view('stock_input_lines.index', [
					'stock_input_lines'=>$stock_input_lines,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$stock_input_lines = DB::table('stock_input_lines')
					->where('line_id','=',$id)
					->paginate($this->paginateValue);

			return view('stock_input_lines.index', [
					'stock_input_lines'=>$stock_input_lines
			]);
	}
}
