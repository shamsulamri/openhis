<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\StockInputBatch;
use Log;
use DB;
use Session;
use App\Product;
use App\StockHelper;
use App\StockInputLine;
use App\StockInput;
use App\DojoUtility;

class StockInputBatchController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$stock_input_batches = DB::table('stock_input_batches')
					->orderBy('batch_number')
					->paginate($this->paginateValue);
			return view('stock_input_batches.index', [
					'stock_input_batches'=>$stock_input_batches
			]);
	}

	public function create()
	{
			$stock_input_batch = new StockInputBatch();
			return view('stock_input_batches.create', [
					'stock_input_batch' => $stock_input_batch,
					'product' => Product::all()->sortBy('product_name')->lists('product_name', 'product_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$stock_input_batch = new StockInputBatch();
			$valid = $stock_input_batch->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$stock_input_batch = new StockInputBatch($request->all());
					$stock_input_batch->batch_id = $request->batch_id;
					$stock_input_batch->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/stock_input_batches/id/'.$stock_input_batch->batch_id);
			} else {
					return redirect('/stock_input_batches/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$stock_input_batch = StockInputBatch::findOrFail($id);
			return view('stock_input_batches.edit', [
					'stock_input_batch'=>$stock_input_batch,
					'product' => Product::all()->sortBy('product_name')->lists('product_name', 'product_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$stock_input_batch = StockInputBatch::findOrFail($id);
			$stock_input_batch->fill($request->input());


			$valid = $stock_input_batch->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$stock_input_batch->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/stock_input_batches/id/'.$id);
			} else {
					return view('stock_input_batches.edit', [
							'stock_input_batch'=>$stock_input_batch,
					'product' => Product::all()->sortBy('product_name')->lists('product_name', 'product_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$stock_input_batch = StockInputBatch::findOrFail($id);
		return view('stock_input_batches.destroy', [
			'stock_input_batch'=>$stock_input_batch
			]);

	}
	public function destroy($id)
	{	
			$batch = StockInputBatch::find($id);
			StockInputBatch::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			//return redirect('/stock_input_batches');
			
			return redirect('/stock_input_batches/batch/'.$batch->line_id);
	}
	
	public function search(Request $request)
	{
			$stock_input_batches = DB::table('stock_input_batches')
					->where('batch_number','like','%'.$request->search.'%')
					->orWhere('batch_id', 'like','%'.$request->search.'%')
					->orderBy('batch_number')
					->paginate($this->paginateValue);

			return view('stock_input_batches.index', [
					'stock_input_batches'=>$stock_input_batches,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$stock_input_batches = DB::table('stock_input_batches')
					->where('batch_id','=',$id)
					->paginate($this->paginateValue);

			return view('stock_input_batches.index', [
					'stock_input_batches'=>$stock_input_batches
			]);
	}

	public function batch($line_id)
	{
			$stock_helper = new StockHelper();

			$line = StockInputLine::find($line_id);
			$stock_input = StockInput::find($line->input_id);
			$batch_current = $stock_helper->getBatches($line->product->product_code, $stock_input->store_code);

			$batches = StockInputBatch::where('line_id',$line_id)->get();

			if ($batches->count()==0) {
				foreach($batch_current as $batch) {
						$new = new StockInputBatch();
						$new->product_code = $batch->product_code;
						$new->batch_number = $batch->batch_number;
						$new->batch_expiry_date = $batch->expiry_date;
						//$new->batch_quantity = $batch->batch_quantity;
						$new->line_id = $line_id;
						$new->input_id = $line->input_id;
						$new->save();
						Log::info($batch->expiry_date);
						Log::info($new->batch_expiry_date);
				}
				$batches = StockInputBatch::where('line_id',$line_id)->get();
			}

			return view('stock_input_batches.batch', [
					'batches'=>$batches,
					'product'=>Product::find($line->product_code),
					'product_code'=>$line->product_code,
					'stock_helper'=>$stock_helper,
					'line_id'=>$line_id,
					'line'=>$line,
					'input_id'=>$line->input_id,
			]);
	}

	public function batchAdd(Request $request)
	{
			$stock_input_batch = new StockInputBatch();
			
			$stock_helper = new StockHelper();
			$this->batchUpdate($request);
			$total = $stock_helper->getStockInputBatchCount($request->line_id)+$request->batch_quantity;
			$line = StockInputLine::find($request->line_id);


			if ($total>$line->line_post_quantity) {
					Session::flash('error', 'Total batch quantity cannot be greater than line quantity.');
					return redirect('/stock_input_batches/batch/'.$request->line_id)
									->withInput();
			}

			if (!empty($request->batch_number)) {
					$valid = $stock_input_batch->validate($request->all(), $request->_method);
					if ($valid->passes()) {
							$stock_input_batch = new StockInputBatch($request->all());
							$stock_input_batch->batch_expiry_date = DojoUtility::dateWriteFormat($request->batch_expiry_date);
							$batch = StockInputBatch::where('line_id',$request->line_id)
										->where('batch_number', $request->batch_number)
										->first();

							if ($batch) {
									$stock_input_batch = $batch;
									$stock_input_batch->batch_quantity = $batch->batch_quantity + $request->batch_quantity;
							} 
							$stock_input_batch->batch_id = $request->batch_id;
							$stock_input_batch->save();


							Session::flash('message', 'Record successfully created.');
							return redirect('/stock_input_batches/batch/'.$request->line_id);
					} else {
							return redirect('/stock_input_batches/batch/'.$request->line_id)
									->withErrors($valid)
									->withInput();
					}
			} else {
					Session::flash('message', 'Record successfully created.');
					return redirect('/stock_input_batches/batch/'.$request->line_id);
			}
	}

	public function batchUpdate($request)
	{
			$line_batches = StockInputBatch::where('line_id', $request->line_id)->get();
			foreach($line_batches as $batch) {
				$batch->batch_quantity = $request['batch_quantity_'.$batch->batch_id];
				$batch->save();
			}

			return $line_batches;
	}

}
