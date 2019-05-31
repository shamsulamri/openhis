<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\PurchaseLine;
use App\DojoUtility;
use Log;
use DB;
use Session;
use App\Product;
use App\UnitMeasure;
use App\TaxCode;
use App\Purchase;
use App\Store;
use App\Inventory;
use App\InventoryMovement;
use Auth;
use App\StockHelper;
use App\ProductUom;
use App\Supplier;
use App\PurchaseRequestStatus;
use App\InventoryBatch;
use App\StockLimit;

class PurchaseLineController extends Controller
{
	public $paginateValue=20;
	public $order_status = array(''=>'','open'=>'Open','close'=>'Close');

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$purchase_lines = PurchaseLine::orderBy('line_id')
					->paginate($this->paginateValue);

			return view('purchase_lines.index', [
					'purchase_lines'=>$purchase_lines
			]);
	}

	public function detail(Request $request, $id)
	{
			$purchase = Purchase::find($id);
			$purchase_lines = PurchaseLine::where('purchase_id', '=', $id)
					->leftJoin('products as b', 'b.product_code', '=', 'purchase_lines.product_code')
					->leftJoin('ref_unit_measures as c', 'c.unit_code', '=', 'purchase_lines.unit_code')
					->orderBy('product_name');

			$purchase_lines = $purchase_lines->paginate($this->paginateValue);

			return view('purchase_lines.detail', [
					'purchase_lines'=>$purchase_lines,
					'purchase_id'=>$id,
					'purchase'=>Purchase::find($id),
					'page'=>$request->page,
					'reload'=>$request->reload,
			]);
	}

	public function convert(Request $request, $from, $to) 
	{
			$reason = $request->reason;
			$items = PurchaseLine::where('purchase_id', '=', $from)->get();

			foreach ($items as $item) {
				$this->convertItem($request->reason, $to, $item->line_id);
			}

			if ($reason == 'purchase') {
					return redirect('/purchases/master_document?reason=purchase&reload=true&purchase_id='.$to);
			} elseif ($reason =='request') {
					return redirect('/purchases/master_document?reason=request&reload=true&move_id='.$to);
			} else {
					return redirect('/purchases/master_document?reason=stock&reload=true&move_id='.$to);
			}
	}

	public function convertItem($reason, $to, $line_id)
	{
			$item = PurchaseLine::find($line_id);

			if ($item) {
				if ($reason == 'purchase') {
						$purchase_line = PurchaseLine::where('purchase_id', $to)
											->where('product_code', $item->product_code)
											->where('unit_code', $item->unit_code)
											->first();

						if (!$purchase_line) {
								$purchase_line = new PurchaseLine();
								$purchase_line->purchase_id = $to;
								$purchase_line->product_code = $item->product_code;
								$purchase_line->line_unit_price = $item->line_unit_price;
								$purchase_line->line_quantity = $item->balanceQuantity();
								$purchase_line->line_subtotal = $item->line_subtotal;
								$purchase_line->unit_code = $item->unit_code;
								$purchase_line->uom_rate = $item->uom_rate;
								$purchase_line->tax_code = $item->tax_code;
								$purchase_line->tax_rate = $item->tax_rate;
								$purchase_line->line_subtotal_tax = $item->line_subtotal_tax;
								$purchase_line->reference_id = $item->line_id;
								$purchase_line->line_posted = 0;
								$purchase_line->save();

						} else {
								Log::info("--->>");
								$purchase_line->purchase_id = $to;
								$purchase_line->product_code = $item->product_code;
								$purchase_line->line_unit_price = $item->line_unit_price;
								$purchase_line->line_quantity = $item->line_quantity + $purchase_line->line_quantity;
								$purchase_line->line_subtotal = $purchase_line->line_quantity*$purchase_line->line_unit_price;
								$purchase_line->unit_code = $item->unit_code;
								$purchase_line->uom_rate = $item->uom_rate;
								$purchase_line->tax_code = $item->tax_code;
								$purchase_line->tax_rate = $item->tax_rate;
								$purchase_line->line_subtotal_tax = $purchase_line->line_subtotal*(1+($purchase_line->tax->tax_rate/100));
								$purchase_line->reference_id = $item->line_id;
								$purchase_line->line_posted = 0;
								$purchase_line->save();
						}
					//}
				} else {
							$movement = InventoryMovement::find($to);

							$helper = new StockHelper();

							$inventory = new Inventory();
							$inventory->move_id = $to;
							$inventory->move_code = $movement->move_code;
							$inventory->store_code = $movement->store_code;
							$inventory->product_code = $item->product_code;
							$inventory->inv_book_quantity = $helper->getStockOnHand($item->product_code);
							$inventory->inv_subtotal = $item->line_subtotal;
							$inventory->inv_physical_quantity = $item->line_quantity;
							$inventory->unit_code = $item->unit_code;
							$inventory->uom_rate = $item->uom_rate;
							$inventory->inv_quantity = $item->line_quantity*$item->uom_rate;
							$inventory->save();
				}
			}
	}

	public function masterItem(Request $request, $id, $document_id = null)
	{
			$reason = $request->reason?:null;
			$purchase = null;
			$movement = null;

			$purchase_lines = PurchaseLine::orderBy('purchase_lines.purchase_id', 'desc')
					->leftjoin('purchases as b', 'b.purchase_id', '=', 'purchase_lines.purchase_id')
					->where('purchase_posted',1)
					->where(function ($query) use ($request) {
							$query->where('author_id', '=', Auth::user()->author_id)
									->orWhere('author_id','=', 7);
					});
					//->where('author_id', '=', Auth::user()->author_id);

			if ($reason=='purchase') {
					$purchase = Purchase::find($id);
					$purchase_lines = $purchase_lines->where('b.purchase_id', '<>', $id);
					//$purchase_lines = $purchase_lines->where('b.supplier_code', '=', $purchase->supplier_code);
			}

			if ($document_id) {
				$purchase_lines = $purchase_lines->where('purchase_lines.purchase_id', '=', $document_id);
			}

			$purchase_lines = $purchase_lines->paginate($this->paginateValue);

			if ($reason=='stock') {
					$movement = InventoryMovement::find($id);
			}

			return view('purchase_lines.master_item', [
					'id' => $id,
					'purchase_lines'=>$purchase_lines,
					'purchase'=> $purchase,
					'movement'=> $movement,
					'reason'=>$reason,
					'document_id'=>$document_id,
					'reload'=>$request->reload,
			]);
	}

	public function multiple(Request $request)
	{
			$reason = $request->reason;
			foreach ($request->all() as $id=>$value) {
					switch ($id) {
							case '_token':
									break;
							case 'current_id':
									break;
							default:
									$this->convertItem($reason, $request->id, $id);
					}
			}

			return redirect('/purchase_lines/master_item/'.$request->id.'/'.$request->document_id.'?reason='.$reason.'&reload=true');
	}

	public function show($id)
	{
			$purchase_line = PurchaseLine::where('purchase_id', '=', $id);
			$purchase = Purchase::find($id);

			return view('purchase_lines.show', [
					'purchase_id' => $id,
					'purchase_line' => $purchase_line,
					'purchase'=>$purchase,
					'author_id'=>Auth::user()->author_id,
					'purchase_request_status' => PurchaseRequestStatus::all()->sortBy('status_name')->lists('status_name', 'status_code')->prepend('',''),
			]);
	}

	public function create()
	{
			$purchase_line = new PurchaseLine();
			return view('purchase_lines.create', [
					'purchase_line' => $purchase_line,
					'unit' => UnitMeasure::all()->sortBy('unit_name')->lists('unit_name', 'unit_code')->prepend('',''),
					'tax' => TaxCode::all()->sortBy('tax_name')->lists('tax_name', 'tax_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$purchase_line = new PurchaseLine();
			$valid = $purchase_line->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$purchase_line = new PurchaseLine($request->all());
					$purchase_line->line_id = $request->line_id;
					$purchase_line->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/purchase_lines/id/'.$purchase_line->line_id);
			} else {
					return redirect('/purchase_lines/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$purchase_line = PurchaseLine::findOrFail($id);
			$product_uoms =  $purchase_line->product->productUnitMeasures();

			$uom_list = [];
			$uom_list['unit'] = 'Unit';
			foreach ($product_uoms as $uom) {
					if ($uom->unit_code != 'unit') {
						$uom_list[$uom->unit_code] = $uom->unitMeasure->unit_name;
					}
			}

			return view('purchase_lines.edit', [
					'purchase_line'=>$purchase_line,
					'product' => Product::find($purchase_line->product_code),
					'uom_list' => $uom_list,
					'product_uoms' => $product_uoms,
					'tax_code' => TaxCode::all()->sortBy('tax_name')->lists('tax_name', 'tax_code')->prepend('',''),
					'purchase' => $purchase_line->purchase,
					'store' => Store::where('store_receiving','=',1)->orderBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$purchase_line = PurchaseLine::findOrFail($id);
			$purchase_line->fill($request->input());

			$product = Product::find($purchase_line->product_code);

			$valid = $purchase_line->validate($request->all(), $request->_method);	

			$tax = TaxCode::find($request->tax_code);

			if ($valid->passes()) {
					$purchase_line->line_subtotal=$purchase_line->line_quantity*$purchase_line->line_unit_price;
					$purchase_line->line_subtotal_tax= $purchase_line->line_subtotal;
					if (!empty($request->tax_code)) {
							$purchase_line->line_subtotal_tax=$purchase_line->line_subtotal_tax*(1+($tax->tax_rate/100));
							$purchase_line->tax_rate = $tax->tax_rate;
					}

					$purchase_line->save();

					Session::flash('message', 'Record successfully updated.');
					return redirect('/purchase_lines/detail/'.$request->purchase_id.'?reload=true');
			} else {
					return view('purchase_lines.edit', [
							'purchase_line'=>$purchase_line,
							'product' => Product::find($purchase_line->product_code),
							])
							->withErrors($valid);			
			}
			/*
			$purchase_line = PurchaseLine::findOrFail($id);
			$purchase_line->fill($request->input());


			$valid = $purchase_line->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$purchase_line->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/purchase_lines/id/'.$id);
			} else {
					return view('purchase_lines.edit', [
							'purchase_line'=>$purchase_line,
					'product' => Product::all()->sortBy('product_name')->lists('product_name', 'product_code')->prepend('',''),
					'unit' => UnitMeasure::all()->sortBy('unit_name')->lists('unit_name', 'unit_code')->prepend('',''),
					'tax' => TaxCode::all()->sortBy('tax_name')->lists('tax_name', 'tax_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
			 */
	}
	
	public function delete($id)
	{
		$purchase_line = PurchaseLine::findOrFail($id);
		return view('purchase_lines.destroy', [
			'purchase_line'=>$purchase_line
			]);

	}

	public function deleteSelection(Request $request, $purchase_id)
	{
			foreach ($request->all() as $id=>$value) {
					switch ($id) {
							case '_token':
									break;
							case 'current_id':
									break;
							default:
									PurchaseLine::find($id)->delete();
					}
			}

			return redirect('/purchase_lines/detail/'.$purchase_id.'?reload=true');
	}

	public function destroy($id)
	{	
			$line = PurchaseLine::find($id);
			PurchaseLine::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/purchase_lines/purchases/'.$line->purchase_id);
	}
	
	public function search(Request $request)
	{
			$reason = $request->reason?:null;
			$purchase = null;
			$movement = null;
			$purchase_lines = PurchaseLine::orderBy('purchase_lines.purchase_id')
					->leftjoin('purchases as b', 'b.purchase_id', '=', 'purchase_lines.purchase_id')
					->leftjoin('products as c', 'c.product_code', '=', 'purchase_lines.product_code')
					->leftjoin('ref_unit_measures as d', 'd.unit_code', '=', 'purchase_lines.unit_code');

			$search = $request->search;
			$search = trim($search, ' ');

			$purchase_lines = $purchase_lines->where(function ($query) use ($request, $search) {
					$query->where('purchase_lines.product_code','like','%'.$search.'%')
					->orWhere('purchase_number', 'like','%'.$search.'%')
					->orWhere('product_name', 'like','%'.$search.'%');
			});

			$purchase_lines = $purchase_lines->where('purchase_posted',1);

			if ($reason=='purchase') {
					$purchase = Purchase::find($request->id);
					$purchase_lines = $purchase_lines->where('b.purchase_id', '<>', $request->id);
					//$purchase_lines = $purchase_lines->where('b.supplier_code', '=', $purchase->supplier_code);
			}

			if (!empty($request->document_id)) {
				$purchase_lines = $purchase_lines->where('purchase_lines.purchase_id', '=', $request->document_id);
			}

			$purchase_lines = $purchase_lines->paginate($this->paginateValue);

			if ($reason=='stock') {
					$movement = InventoryMovement::find($request->id);
			}

			return view('purchase_lines.master_item', [
					'id' => $request->id,
					'purchase_lines'=>$purchase_lines,
					'purchase'=> $purchase,
					'movement'=> $movement,
					'reason'=>$request->reason,
					'document_id'=>$request->document_id,
					'reload'=>null,
					'search'=>$request->search,
			]);
	}

	public function searchById($id)
	{
			$purchase_lines = DB::table('purchase_lines')
					->where('line_id','=',$id)
					->paginate($this->paginateValue);

			return view('purchase_lines.index', [
					'purchase_lines'=>$purchase_lines
			]);
	}

	public function add($purchase_id, $product_code, $type=null, $quantity=0)
	{
			if ($type=='reorder') {
				$purchase = Purchase::find($purchase_id);
				$store_code = $purchase->store_code;

				$reorder = StockLimit::where('store_code',$store_code)
								->where('product_code',$product_code)
								->first();

				if ($reorder) {
					$quantity = $reorder->reorder_quantity;
				} 
			}

			$purchase_line = PurchaseLine::where('purchase_id', $purchase_id)
							->where('product_code', $product_code)
							->first();

			if (!$purchase_line) {
					$purchase_line = new PurchaseLine();
			}

			$purchase = Purchase::find($purchase_id);
			$product = Product::find($product_code);
			$default_tax = TaxCode::where('tax_default',1)->first();

			$purchase_line->purchase_id = $purchase_id;
			$purchase_line->product_code = $product_code;
			$purchase_line->line_quantity += $quantity;
			$purchase_line->uom_rate = $product->uomDefaultCost()->uom_rate;
			$purchase_line->unit_code = $product->uomDefaultCost()->unit_code;
			$purchase_line->line_unit_price = $product->uomDefaultCost()->uom_cost;
			$purchase_line->line_subtotal = $purchase_line->line_quantity*$purchase_line->line_unit_price;
			if ($product->product_input_tax) {
				$purchase_line->tax_code = $product->product_input_tax;
				$purchase_line->tax_rate = isset($product->product_input_tax) ? $product->inputTax->tax_rate : 0;
			} else {
				$purchase_line->tax_code = $default_tax->tax_code;
				$purchase_line->tax_rate = isset($default_tax->tax_rate) ? $default_tax->tax_rate : 0;
			}

			if ($purchase_line->tax_rate>0) {
				$purchase_line->line_subtotal_tax = $purchase_line->line_subtotal*(1+$default_tax->tax_rate/100);
			} else {
				$purchase_line->line_subtotal_tax = $purchase_line->line_subtotal;
			}
			$purchase_line->save();

			Session::flash('message', 'Record added successfully.');
			if ($type=='reorder') {
					return redirect('/product_searches?reason=purchase&type=reorder&purchase_id='.$purchase_id.'&line_id='.$purchase->line_id);
			} else {
					return redirect('/product_searches?reason=purchase&purchase_id='.$purchase_id.'&line_id='.$purchase->line_id);
			}
	}

	public function addReorder($purchase_id)
	{
		$purchase = Purchase::find($purchase_id);
		$sql = "
				select a.product_code, limit_min, reorder_quantity, stock_quantity
				from stock_limits as a
				left join (
					select product_code, sum(inv_quantity) as stock_quantity
					from inventories
					where store_code = '". $purchase->store_code ."'
					group by product_code
				) as b on (b.product_code = a.product_code)
				where a.store_code = '". $purchase->store_code ."'
				having stock_quantity<limit_min 
				or stock_quantity is null 
				and reorder_quantity>0
		";

		$reorders = DB::select($sql);

		Log::info($purchase_id);
		foreach ($reorders as $reorder) {

			$this->add($purchase_id, $reorder->product_code, 'reorder', $reorder->reorder_quantity);
			Log::info($reorder->reorder_quantity);
		}

		Session::flash('message', 'Record added successfully.');
		return redirect('/product_searches?reason=purchase&type=reorder&purchase_id='.$purchase_id);
	}

	public function goodsReceive($id) 
	{
		$purchase = Purchase::find($id);
		$purchase_lines = PurchaseLine::where('purchase_id', $id)
							->where('line_posted',0)
							->get();

		$helper = new StockHelper();

		foreach ($purchase_lines as $line) {
			$inventory = new Inventory();
			$inventory->line_id = $line->line_id;
			$inventory->move_code = 'goods_receive';
			$inventory->store_code = $purchase->store_code;
			$inventory->product_code = $line->product_code;
			$inventory->unit_code = $line->unit_code;
			$inventory->uom_rate = $line->uom_rate;
			$inventory->inv_quantity = $line->line_quantity*$line->uom_rate;
			$inventory->inv_physical_quantity = $line->line_quantity;
			$inventory->inv_unit_cost = $line->line_unit_price;
			$inventory->inv_subtotal = $line->line_subtotal;
			$inventory->inv_batch_number = $line->batch_number;
			$inventory->inv_posted = 1;
			$inventory->username = Auth::user()->id;
			$inventory->save();

			$line->line_posted = 1;
			$line->save();

			$this->updateCost($line);

			$helper->updateStockOnHand($inventory->product_code);
		}

		Session::flash('message', 'Document posted.');
		return redirect('/purchase_lines/detail/'.$id);
	}

	public function updateCost($purchase_line)
	{
			/** Save last cost price **/
			$uom = $purchase_line->product->uom->where('unit_code',$purchase_line->unit_code)->first();
			if (!empty($uom)) {
					$uom->uom_cost = $purchase_line->line_unit_price;
					$uom->save();
			} else {
					$uom = new ProductUom();
					$uom->product_code = $purchase_line->product_code;
					$uom->unit_code = 'unit';
					$uom->uom_cost = $purchase_line->line_unit_price;
					$uom->uom_rate = 1;
					$uom->save();
			}
	}


	public function post_confirmation($id)
	{
		return view('purchase_lines.confirm', [
			'id'=>$id
		]);

	}

	public function close(Request $request, $id)
	{
		$purchase = Purchase::find($id);
		$purchase->status_code = $request->status_code;
		$purchase->save();

		return redirect('/purchase/search?document_code=purchase_request');
	}

	public function post($id)
	{
		$purchase = Purchase::find($id);
		$purchase->purchase_posted = 1;
		$purchase->save();

		$purchase_lines = PurchaseLine::where('purchase_id', $id)
							->where('line_posted',0)
							->get();

		foreach ($purchase_lines as $line) {
			$this->updateCost($line);
			if (!empty($line->batch_number)) {
				$batches = InventoryBatch::where('batch_number', $line->batch_number)->get();
				if ($batches->count()==0) {
						$batch = new InventoryBatch();
						$batch->batch_number = $line->batch_number;
						$batch->product_code = $line->product_code;
						$batch->batch_expiry_date = $line->expiry_date;
						$batch->save();
				}
			}	
		}

		if ($purchase->document_code == 'goods_receive') {
				$this->goodsReceive($id);
		}

		return redirect('/purchases');
	}

	public function enquiry(Request $request)
	{
			$purchase_lines = PurchaseLine::select('*', 'e.unit_name')
								->leftJoin('purchases as b', 'b.purchase_id', '=', 'purchase_lines.purchase_id')
								->leftJoin('products as c', 'c.product_code', '=', 'purchase_lines.product_code')
								->leftJoin('suppliers as d', 'd.supplier_code', '=', 'b.supplier_code')
								->leftJoin('ref_unit_measures as e', 'e.unit_code', '=', 'purchase_lines.unit_code')
								->whereIn('category_code', Auth::user()->categoryCodes());

			if (!empty($request->document_number)) {
				$purchase_lines = $purchase_lines->where('purchase_number','like', '%'.$request->document_number.'%');
			}

			if (!empty($request->product_name)) {
				$purchase_lines = $purchase_lines->where('product_name','like', '%'.$request->product_name.'%');
			}

			if (!empty($request->supplier_code)) {
				$purchase_lines = $purchase_lines->where('b.supplier_code','=', $request->supplier_code);
			}

			/*** Status ***/
			if (!empty($request->status_code)) {
				switch($request->status_code) {
						case "close":
								$purchase_lines = $purchase_lines->where('purchase_posted','=', 1 );
								break;
						case "open":
								$purchase_lines = $purchase_lines->where('purchase_posted','=', 0 );
								break;
				} 
			}

			/*** Date Range ****/
			$date_start = DojoUtility::dateWriteFormat($request->date_start);
			$date_end = DojoUtility::dateWriteFormat($request->date_end);

			if (!empty($request->date_start) && empty($request->date_end)) {
				$purchase_lines = $purchase_lines->where('b.created_at', '>=', $date_start.' 00:00');
			}

			if (empty($request->date_start) && !empty($request->date_end)) {
				$purchase_lines = $purchase_lines->where('b.created_at', '<=', $date_end.' 23:59');
			}

			if (!empty($request->date_start) && !empty($request->date_end)) {
				$purchase_lines = $purchase_lines->whereBetween('b.created_at', array($date_start.' 00:00', $date_end.' 23:59'));
			} 

			if ($request->export_report) {
				$purchase_lines = $purchase_lines->select('purchase_number', 'b.created_at', 'supplier_name', 'product_name', 'c.product_code', 'line_quantity', 'unit_name', 'line_unit_price' );
				$purchase_lines = $purchase_lines->get()->toArray();
				DojoUtility::export_report($purchase_lines);
			}

			$purchase_lines = $purchase_lines->paginate($this->paginateValue);

			return view('purchase_lines.enquiry', [
				'purchase_lines'=>$purchase_lines,
				'store'=>Auth::user()->storeList()->prepend('',''),
				'store_code'=>$request->store_code,
				'product_name'=>$request->product_name,
				'date_start'=> $date_start,
				'date_end'=> $date_end,
				'document_number'=>$request->document_number,
				'order_status'=> $this->order_status,
				'status_code'=>$request->status_code,
				'supplier_code'=>$request->supplier_code,
				'supplier' => Supplier::all()->sortBy('supplier_name')->lists('supplier_name', 'supplier_code')->prepend('',''),
			]);
	}
}
