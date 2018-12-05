<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\PurchaseLine;
use Log;
use DB;
use Session;
use App\Product;
use App\UnitMeasure;
use App\TaxCode;
use App\Purchase;
use App\Store;
use App\Inventory;
use Auth;

class PurchaseLineController extends Controller
{
	public $paginateValue=20;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$purchase_lines = PurchaseLine::orderBy('product_code')
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
			]);
	}

	public function convert($from, $to) 
	{
			$items = PurchaseLine::where('purchase_id', '=', $from)->get();

			foreach ($items as $item) {
				$this->convertItem($to, $item->line_id);
			}

			return redirect('/purchases/master_document/'.$to);
	}

	public function convertItem($purchase_id, $line_id)
	{
			$item = PurchaseLine::find($line_id);

			if ($item) {
					if ($item->balanceQuantity()) {
							$purchase_line = new PurchaseLine();
							$purchase_line->purchase_id = $purchase_id;
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
							$purchase_line->save();
					}
			}
	}

	public function masterItem($id, $document_id = null)
	{
			$purchase_lines = PurchaseLine::orderBy('purchase_id')
					->where('purchase_id', '<>', $id);

			if ($document_id) {
				$purchase_lines = $purchase_lines->where('purchase_id', '=', $document_id);
			}

			$purchase_lines = $purchase_lines->paginate($this->paginateValue);

			return view('purchase_lines.master_item', [
					'purchase_id' => $id,
					'purchase_lines'=>$purchase_lines,
					'purchase'=> Purchase::find($id),
			]);
	}

	public function multiple(Request $request)
	{
			foreach ($request->all() as $id=>$value) {
					switch ($id) {
							case '_token':
									break;
							case 'current_id':
									break;
							default:
									$this->convertItem($request->purchase_id, $id);
					}
			}

			return redirect('/purchase_lines/master_item/'.$request->purchase_id);
	}

	public function show($id)
	{
			$purchase_line = PurchaseLine::where('purchase_id', '=', $id);
			$purchase = Purchase::find($id);

			$reasons = array('PQ'=>'purchase_request', 'PO'=>'purchase_order', 'GR'=>'goods_receive');

			return view('purchase_lines.show', [
					'purchase_id' => $id,
					'purchase_line' => $purchase_line,
					'purchase'=>$purchase,
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
					return redirect('/purchase_lines/detail/'.$request->purchase_id);
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
									$purchase_line = PurchaseLine::find($id);
									PurchaseLine::find($id)->delete();
					}
			}

			return redirect('/purchase_lines/detail/'.$purchase_id);
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
			$purchase_lines = DB::table('purchase_lines')
					->where('product_code','like','%'.$request->search.'%')
					->orWhere('line_id', 'like','%'.$request->search.'%')
					->orderBy('product_code')
					->paginate($this->paginateValue);

			return view('purchase_lines.index', [
					'purchase_lines'=>$purchase_lines,
					'search'=>$request->search
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

	public function add($purchase_id, $product_code)
	{
			$purchase = Purchase::find($purchase_id);

			$product = Product::find($product_code);
			$default_tax = TaxCode::where('tax_default',1)->first();
			$purchase_line = new PurchaseLine();
			$purchase_line->purchase_id = $purchase_id;
			$purchase_line->product_code = $product_code;
			$purchase_line->line_unit_price = $product->product_purchase_price;
			$purchase_line->line_quantity = 1;
			$purchase_line->line_subtotal = $purchase_line->line_quantity*$purchase_line->line_unit_price;
			$purchase_line->unit_code = 'unit';
			if ($product->product_input_tax) {
				$purchase_line->tax_code = $product->product_input_tax;
				$purchase_line->tax_rate = isset($product->product_input_tax) ? $product->inputTax->tax_rate : 0;
			} else {
				$purchase_line->tax_code = $default_tax->tax_code;
				$purchase_line->tax_rate = isset($default_tax->tax_rate) ? $default_tax->tax_rate : 0;
			}

			if ($purchase_line->tax_rate>0) {
				$purchase_line->line_subtotal_tax = $product->product_purchase_price*(1+$default_tax->tax_rate/100);
			} else {
				$purchase_line->line_subtotal_tax = $purchase_line->line_subtotal;
			}
			$purchase_line->save();

			Session::flash('message', 'Record added successfully.');
			return redirect('/product_searches?reason='.$purchase->purchase_document.'&purchase_id='.$purchase_id.'&line_id='.$purchase->line_id);
	}

	public function post($id) 
	{
		$purchase_lines = PurchaseLine::where('purchase_id', $id)
							->get();

		foreach ($purchase_lines as $line) {
			$inventory = new Inventory();
			$inventory->line_id = $line->line_id;
			$inventory->move_code = 'goods_receive';
			$inventory->store_code = $line->store_code;
			$inventory->product_code = $line->product_code;
			$inventory->unit_code = $line->unit_code;
			$inventory->uom_rate = $line->uom_rate;
			$inventory->inv_quantity = $line->line_quantity*$line->uom_rate;
			$inventory->inv_physical_quantity = $line->line_quantity;
			$inventory->inv_unit_cost = $line->line_unit_price;
			$inventory->inv_subtotal = $line->line_subtotal;
			$inventory->inv_batch_number = $line->batch_number;
			$inventory->inv_expiry_date = $line->expiry_date;
			$inventory->inv_posted = 1;
			$inventory->username = Auth::user()->id;
			Log::info($line->uom_rate);
			$inventory->save();
		}

		Session::flash('message', 'Document posted.');
		return redirect('/purchase_lines/detail/'.$id);
	}


	public function post_confirmation($id)
	{
		return view('purchase_lines.confirm', [
			'id'=>$id
		]);

	}
}
