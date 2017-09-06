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
use App\StoreAuthorization;
use App\StockInputBatch;
use App\StockBatch;
use App\PurchaseOrder;
use App\StockReceive;
use App\Loan;
use App\Ward;
use App\ProductAuthorization;

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
					->orderBy('input_id','desc')
					->paginate($this->paginateValue);
			return view('stock_inputs.index', [
					'stock_inputs'=>$stock_inputs
			]);
	}

	public function create()
	{
			$stores = StoreAuthorization::where('author_id', Auth::user()->author_id)
							->leftjoin('stores as b','b.store_code','=','store_authorizations.store_code')
							->orderBy('store_name')->lists('store_name', 'b.store_code')->prepend('','');

			$stock_input = new StockInput();

			return view('stock_inputs.create', [
					'stock_input' => $stock_input,
					'move' => StockMovement::where('move_code','<>','sale')
							->where('move_code','<>','receive')
							->orderBy('move_name')
							->lists('move_name', 'move_code')
							->prepend('',''),
					'store' => Auth::user()->storeList(),
					'store_target' => Store::orderBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$stock_input = new StockInput();
			$valid = $stock_input->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$stock_input = new StockInput($request->all());
					if (empty($stock_input->input_description)) {
							$stock_input->input_description = $stock_input->movement->move_name.": ".$stock_input->store->store_name;
							if ($stock_input->move_code=='transfer') {
								$stock_input->input_description = $stock_input->input_description." to ".$stock_input->store_transfer->store_name;
							}
					}
					$stock_input->input_id = $request->input_id;
					$stock_input->username = Auth::user()->username;
					$stock_input->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/stock_inputs/show/'.$stock_input->input_id);
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
		$lines = StockInputLine::where('input_id', $id);

			StockInput::find($id)->delete();
			DB::table('loans')
					->whereIn('input_line_id',$lines->pluck('line_id'))
					->update(['input_line_id'=>null]);

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

	public function show($id)
	{
			$stock_input = StockInput::find($id);
			$purchase_order = PurchaseOrder::find($stock_input->purchase_id);
			$stock_receive = StockReceive::where('input_id', $id)->first();
			if (!$stock_receive) {
					$stock_receive = new StockReceive();
			}

			return view('stock_inputs.show',[
					'stock_input'=>$stock_input,
					'purchase_order'=>$purchase_order,
					'store' => Store::where('store_receiving','=',1)->orderBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
					'stock_helper'=>new StockHelper,
					'stock_receive'=>$stock_receive,
			]);
	}

	public function input($id, $product_code=null)
	{
			$product = new Product();
			$stock_input = StockInput::find($id);
			$input_lines = StockInputLine::where('input_id', $id)
							->leftjoin('products as b', 'b.product_code','=','stock_input_lines.product_code')
							->orderBy('product_name')
							->get();

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
					'stock_helper'=>new StockHelper(),
					'input_id'=>$id,
			]);
	}

	public function save(Request $request, $id)
	{
		$lines = StockInputLine::where('input_id', $id)->get();
		foreach ($lines as $line) {
				$line->line_quantity = $request['quantity_'.$line->line_id];
				$line->line_value = $request['value_'.$line->line_id];
				$line->line_difference = $line->line_snapshot_quantity - $line->line_quantity;
				$line->save();
		}

		Session::flash('message', 'Record successfully updated.');
		return redirect('/stock_inputs/input/'.$id);

	}

	public function post(Request $request, $id)
	{
			$stock_helper = new StockHelper();
			$stock_input = StockInput::find($id);

			/** Validation **/
			$valid=null;
			if ($stock_input->move_code=='receive') {
					if (empty($request->store_code)) {
						$valid['store_code']='This field is required.';
					}

					if (empty($request->invoice_number)) {
						$valid['invoice_number']='This field is required.';
					}

					if (empty($request->invoice_date)) {
						$valid['invoice_date']='This field is required.';
					}
			}

			$input_lines = StockInputLine::where('input_id', $id)->get();
			foreach($input_lines as $line) {
					if ($line->product->product_track_batch == 1) {
							$batch_receive = $stock_helper->getStockInputBatchCount($line->line_id); 
							$batch_quantity = $line->line_quantity;
							if ($batch_receive != $batch_quantity) {
									$valid['batch']='Incomplete batch quantity.';
							}
					}

					if ($stock_input->move_code == 'transfer') {
							$on_hand = $stock_helper->getStockCountByStore($line->product_code, $stock_input->store_code);
							if ($on_hand==0) {
									$valid['stock']='Insufficient stock.';
							}
					}
			}

			if (!empty($valid)) {
					return redirect('/stock_inputs/show/'.$id)
							->withErrors($valid)
							->withInput();
			} 
			/** Validation:END **/


			if ($stock_input->move_code == 'receive') {
				$stock_input->store_code = $request->store_code;
				
				$stock_receive = new StockReceive();
				$stock_receive->input_id = $stock_input->input_id;
				$stock_receive->invoice_number = $request->invoice_number;
				$stock_receive->invoice_date = $request->invoice_date;
				$stock_receive->store_code = $request->store_code;
				$stock_receive->delivery_number = $request->delivery_number;
				$stock_receive->save();
				
				if ($request->close_transaction==1) {
						$purchase_order = PurchaseOrder::find($stock_input->purchase_id);
						$purchase_order->purchase_received=1;
						$purchase_order->purchase_close = DojoUtility::now();
						$purchase_order->save();
				}
			} else {
					$stock_input->input_description=$request->input_description;
			}

			$stock_input->input_close=1;
			$stock_input->save();

			$input_lines = StockInputLine::where('input_id', $id)->get();
			foreach($input_lines as $input_line) {
				$this->input_post($input_line);
			}


			return redirect('/stock_inputs');
	}

	public function input_post($line)
	{
			$stock_helper = new StockHelper();

			$product = $line->product;
			$stock_input = StockInput::find($line->input_id);
			$stock_store = StockStore::where('store_code',$stock_input->store_code)
							->where('product_code', $product->product_code)
							->first();

			$conversion=1;
			if ($stock_input->move_code == 'receive') $conversion = $line->product->product_conversion_unit;
			if ($conversion==0) $conversion=1;

			if (!empty($line->line_quantity) && !empty($product)) {
				$stock = new Stock();
				$stock->username = Auth::user()->username;
				$stock->move_code = $stock_input->move_code;
				$stock->store_code = $stock_input->store_code;
				$stock->product_code = $product->product_code;
				$stock->stock_quantity = $line->line_quantity*$conversion;
				$stock->stock_conversion_unit = $line->product->product_conversion_unit;
				$stock->stock_value = $line->line_value;
				$stock->batch_number = $line->batch_number;
				$stock_datetime = DojoUtility::now();
				$stock->stock_datetime = $stock_datetime;
				$stock->stock_description = $stock_input->input_description;
				$stock->line_id = $line->po_line_id;
				$stock->store_code_transfer = $stock_input->store_code_transfer;

				$stock = $stock_helper->moveStock($stock);

				/** Update stock batches **/
				if ($stock->product->product_track_batch==1) {
					if ($stock->move_code=='take') {
							StockBatch::where('product_code','=', $stock->product_code)->delete();
					}
					$line_batches = StockInputBatch::where('input_id', $line->input_id)->get();
					foreach($line_batches as $batch) {
							$conversion=1;
							if ($stock_input->move_code == 'receive') $conversion = $batch->StockInputLine->product->product_conversion_unit;
							if ($stock_input->move_code == 'transfer') {
									$new_batch = new StockBatch();
									$new_batch->stock_id = $stock->stock_id;
									$new_batch->store_code = $stock->store_code_transfer;
									$new_batch->product_code = $batch->product_code;
									$new_batch->batch_number = $batch->batch_number;
									$new_batch->batch_quantity = $batch->batch_quantity*$conversion;
									$new_batch->expiry_date = $batch->batch_expiry_date;
									$new_batch->save();

									$new_batch = new StockBatch();
									$new_batch->stock_id = $stock->stock_id;
									$new_batch->store_code = $stock->store_code;
									$new_batch->product_code = $batch->product_code;
									$new_batch->batch_number = $batch->batch_number;
									$new_batch->batch_quantity = -($batch->batch_quantity*$conversion);
									$new_batch->expiry_date = $batch->batch_expiry_date;
									$new_batch->save();
							} else {
									$new_batch = new StockBatch();
									$new_batch->stock_id = $stock->stock_id;
									$new_batch->store_code = $stock->store_code;
									$new_batch->product_code = $batch->product_code;
									$new_batch->batch_number = $batch->batch_number;
									$new_batch->batch_quantity = $batch->batch_quantity*$conversion;
									$new_batch->expiry_date = $batch->batch_expiry_date;
									$new_batch->save();
							}
					}
				}
			}
	}

	public function input_close($id)
	{
			$stock_input = StockInput::find($id);
			$stock_input->input_close=1;
			$stock_input->save();
			return redirect('/stock_inputs');

	}

	public function receive(Request $request, $id) 
	{
			return $id;
	}

	public function indent($id)
	{
			$stock_input = StockInput::find($id);
			$store = Ward::where('store_code','=',$stock_input->store_code_transfer)->first();

			if ($store) {
					$loans = Loan::where('ward_code','=', $store->ward_code)
								->where('loan_code', '=', 'accept')
								->whereNull('input_line_id');
					$loans = $loans->leftjoin('products as b','b.product_code','=', 'loans.item_code');

					$product_authorization = ProductAuthorization::select('category_code')->where('author_id', Auth::user()->author_id);
					if (!$product_authorization->get()->isEmpty()) {
							$loans = $loans->whereIn('b.category_code',$product_authorization->pluck('category_code'));
					}

					$loans = $loans->get();

					$stock_helper = new StockHelper();

					foreach ($loans as $loan) {
						$pre_quantity = $stock_helper->getStockCountByStore($loan->item_code, $stock_input->stre_code);
						$product = Product::find($loan->item_code);
						$input_line = new StockInputLine();
						$input_line->input_id = $stock_input->input_id;
						$input_line->product_code = $loan->item_code;
						$input_line->line_value = $loan->loan_quantity*$product->product_average_cost;
						$input_line->line_snapshot_quantity = $pre_quantity;
						$input_line->line_quantity = $loan->loan_quantity;
						$input_line->save();
						$loan->input_line_id = $input_line->line_id;
						$loan->save();
					}

					Session::flash('message', $loans->count().' items indented.');
			} else {
					Session::flash('message', 'No items indented.');
			}

			return redirect('/stock_inputs/input/'.$stock_input->input_id);
	}
}
