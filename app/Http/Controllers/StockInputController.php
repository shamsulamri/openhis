<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\StockInput;
use Log;
use DB;
use Session;
use App\StockMovement;
use App\Store;
use App\Product;
use App\Stock;
use App\Http\Controllers\ProductController;
use Auth;
use App\DojoUtility;
use App\StockInputLine;
use App\StockStore;
use App\StockHelper;

class StockInputController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$stock_inputs = StockInput::where('username', Auth::user()->username)
					->where('input_close',0)
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
					'move' => StockMovement::all()->sortBy('move_name')->lists('move_name', 'move_code')->prepend('',''),
					'store' => Store::all()->sortBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$stock_input = new StockInput();
			$valid = $stock_input->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$stock_input = new StockInput($request->all());
					$stock_input->input_id = $request->input_id;
					$stock_input->username = Auth::user()->username;
					$stock_input->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/stock_inputs/input/'.$stock_input->input_id);
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
					'move' => StockMovement::all()->sortBy('move_name')->lists('move_name', 'move_code')->prepend('',''),
					'store' => Store::all()->sortBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
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
					'move' => StockMovement::all()->sortBy('move_name')->lists('move_name', 'move_code')->prepend('',''),
					'store' => Store::all()->sortBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
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

	public function input($id, $product_code=null)
	{
			$product = new Product();
			$stock_input = StockInput::find($id);
			$input_lines = StockInputLine::where('input_id', $id)->orderBy('line_id','desc')->get();
			$stock_store = new StockStore();
			if (!empty($product_code)) {
					$product = Product::find($product_code);
					$stock_store = StockStore::where('store_code',$stock_input->store_code)
							->where('product_code', $product_code)
							->first();
			}

			return view('stock_inputs.input', [
					'stock_input'=>$stock_input,
					'product'=>$product,
					'input_lines'=>$input_lines,
					'stock_store'=>$stock_store,
			]);
	}

	public function input_post(Request $request)
	{
			$product = Product::find($request->product_code);
			if (empty($request->product_code)) {
				$product = new Product();	
			}
			$stock_input = StockInput::find($request->input_id);
			$input_lines = StockInputLine::where('input_id', $request->input_id)->orderBy('line_id','desc')->get();
			$stock_store = StockStore::where('store_code',$stock_input->store_code)
							->where('product_code', $request->product_code)
							->first();

			$stock_store_quantity=0;

			if (!empty($request->amount_new) && !empty($product)) {
				if (empty($stock_store)) {
						Session::flash('error', 'Insufficient quantity.');
						return redirect('/stock_inputs/input/'.$request->input_id.'/'.$request->product_code)
									->withInput();
				}
				if (!empty($stock_store->stock_quantity)) {
						$stock_store_quantity = $stock_store->stock_quantity;
						if ($request->amount_new>$stock_store_quantity && $stock_input->move_code=='transfer') {
								Session::flash('error', 'New amount cannot be greater than current.');
								return redirect('/stock_inputs/input/'.$request->input_id.'/'.$request->product_code)
											->withInput();
						}
				}
				$line = new StockInputLine();
				$line->input_id = $stock_input->input_id;
				$line->product_code = $product->product_code;
				$line->amount_current = $stock_store_quantity;
				$line->amount_new = $request->amount_new;
				$line->batch_number = $request->batch_number;
				$line->amount_difference = $request->amount_new - $stock_store_quantity;
				$line->save();

				$stock = new Stock();
				$stock->username = Auth::user()->username;
				$stock->move_code = $stock_input->move_code;
				$stock->store_code = $stock_input->store_code;
				$stock->product_code = $product->product_code;
				$stock->stock_quantity = $request->amount_new;
				$stock->batch_number = $request->batch_number;
				$stock_datetime = DojoUtility::now();
				$stock->stock_datetime = $stock_datetime;

				switch($stock_input->move_code) {
					case "dispose":
							$stock->stock_quantity = abs($stock->stock_quantity);
							$stock->stock_quantity = -($stock->stock_quantity);
							$stock->save();
							break;
					case "return":
							$stock->stock_quantity = abs($stock->stock_quantity);
							$stock->stock_quantity = -($stock->stock_quantity);
							$stock->save();
							break;
					case "transfer":
							$store = Store::find($stock_input->store_code_transfer);
							$stock->store_code_transfer = $stock_input->store_code_transfer;
							$stock->stock_quantity = abs($stock->stock_quantity);
							$stock->stock_quantity = ($stock->stock_quantity)*-1;
							$stock->stock_description = "Transfer out to ".$store->store_name;
							$stock->save();

							$store = Store::find($stock->store_code);
							$transfer = new Stock();
							$transfer->product_code = $stock->product_code;
							$transfer->username = $stock->username;
							$transfer->stock_datetime = $stock_datetime;
							$transfer->move_code = $stock->move_code;
							$transfer->store_code_transfer = $stock->store_code;
							$transfer->store_code = $stock->store_code_transfer;
							$transfer->stock_quantity = abs($stock->stock_quantity);
							$transfer->stock_tag = $stock->stock_id;
							$transfer->stock_description = "Transfer in from ".$store->store_name;
							$transfer->save();
							break;
					default:
							$stock->save();
				}

				$stock_helper = new StockHelper();
				$stock_helper->updateAllStockOnHand($stock->product_code);

				$product= new Product();
				return redirect('/stock_inputs/input/'.$stock_input->input_id);
			}

			return view('stock_inputs.input', [
					'stock_input'=>$stock_input,
					'product'=>$product,
					'input_lines'=>$input_lines,
					'stock_store'=>$stock_store,
			]);
	}

	public function input_close($id)
	{
			$stock_input = StockInput::find($id);
			$stock_input->input_close=1;
			$stock_input->save();
			return redirect('/stock_inputs');

	}
}
