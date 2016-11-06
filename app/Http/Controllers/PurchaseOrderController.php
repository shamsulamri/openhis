<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\PurchaseOrder;
use DB;
use Session;
use App\Supplier;
use App\Store;
use Auth;
use App\DojoUtility; 
use Carbon\Carbon;
use Log;
use App\PurchaseOrderLine;
use App\Stock;
use App\Http\Controllers\ProductController;
use App\Product;
use App\DietClass;
use App\DietHelper;
use App\Diet;

class PurchaseOrderController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$purchase_orders = DB::table('purchase_orders as a')
					->leftJoin('suppliers as b', 'b.supplier_code','=','a.supplier_code')
					->where('author_id', '=', Auth::user()->author_id)
					->orderBy('purchase_date','desc')
					->paginate($this->paginateValue);
			return view('purchase_orders.index', [
					'purchase_orders'=>$purchase_orders
			]);
	}

	public function create()
	{
			$purchase_order = new PurchaseOrder();
			$today = date('d/m/Y', strtotime(Carbon::now()));  
			$purchase_order->purchase_date = $today;

			return view('purchase_orders.create', [
					'purchase_order' => $purchase_order,
					'supplier' => Supplier::all()->sortBy('supplier_name')->lists('supplier_name', 'supplier_code')->prepend('',''),
					'store' => Store::all()->sortBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
					'maxYear' => Carbon::now()->year,
					]);
	}

	public function store(Request $request) 
	{
			$purchase_order = new PurchaseOrder();
			$purchase_order->user_id = Auth::user()->id;

			$valid = $purchase_order->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$purchase_order = new PurchaseOrder($request->all());
					$purchase_order->purchase_id = $request->purchase_id;
					$purchase_order->author_id = Auth::user()->author_id;
					$purchase_order->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/purchase_orders/id/'.$purchase_order->purchase_id);
			} else {
					return redirect('/purchase_orders/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$purchase_order = PurchaseOrder::findOrFail($id);
			if ($purchase_order->purchase_posted==1) {
					if ($purchase_order->purchase_received==0) {
						$purchase_order->receive_datetime = date('d/m/Y H:i', strtotime(Carbon::now())); 
					}
					//$purchase_order->receive_datetime = '03/03/2016 10:33';
					//return $purchase_order->receive_datetime;
			}
			return view('purchase_orders.edit', [
					'purchase_order'=>$purchase_order,
					'supplier' => Supplier::all()->sortBy('supplier_name')->lists('supplier_name', 'supplier_code')->prepend('',''),
					'store' => Store::all()->sortBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
					'maxYear' => Carbon::now()->year,
					]);
	}

	public function update(Request $request, $id) 
	{
			$purchase_order = PurchaseOrder::findOrFail($id);
			$purchase_order->fill($request->input());

			$valid = $purchase_order->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$purchase_order->save();
					if ($purchase_order->purchase_received==0) {
						$this->stockReceive($id);
						$purchase_order->purchase_received=1;
						$purchase_order->save();
					}
					Session::flash('message', 'Record successfully updated.');
					return redirect('/purchase_order_lines/index/'.$id);
			} else {
					return redirect('/purchase_orders/'.$id.'/edit')
							->withErrors($valid)
							->withInput();
					return "X";
					return view('purchase_orders.edit', [
							'purchase_order'=>$purchase_order,
							'supplier' => Supplier::all()->sortBy('supplier_name')->lists('supplier_name', 'supplier_code')->prepend('',''),
							'store' => Store::all()->sortBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
							'maxYear' => Carbon::now()->year,
							])
							->withErrors($valid);			
			}
	}
	
	public function stockReceive($id)
	{
			$purchase_order = PurchaseOrder::find($id);
			$line_items = PurchaseOrderLine::where('purchase_id','=',$id)
					->get();

			foreach ($line_items as $item) {
					$product = Product::find($item->product_code);
					$source = Product::find($item->product_code);
					if (!empty($product->product_conversion_code)) {
							$conversion_code = $product->product_conversion_code;
							$product = Product::find($conversion_code);
					}
					$stock = new Stock();
					$stock->line_id = $item->line_id;
					$stock->store_code = $purchase_order->store_code;
					$stock->move_code = 'receive';
					$stock->product_code = $product->product_code;
					$stock->stock_date = DojoUtility::now();
					$stock->stock_quantity = ($item->line_quantity_received + $item->line_quantity_received_2);
					if ($source->product_conversion_unit>0) {
						$stock->stock_quantity = ($item->line_quantity_received + $item->line_quantity_received_2)*$source->product_conversion_unit;
					}
					$stock->stock_description = "Purchase id: ".$purchase_order->purchase_id;
					$stock->save();
					Log::info('---'.$item->product_code);
					Log::info('-------'.$item->product_conversion_unit);

					$productController = new ProductController();
					$productController->updateTotalOnHand($product->product_code);
			}
			return $line_items;
	}

	public function delete($id)
	{
		$purchase_order = PurchaseOrder::findOrFail($id);
		return view('purchase_orders.destroy', [
			'purchase_order'=>$purchase_order
			]);

	}
	public function destroy($id)
	{	
			PurchaseOrder::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/purchase_orders');
	}
	
	public function search(Request $request)
	{
			$purchase_orders = DB::table('purchase_orders as a')
					->leftJoin('suppliers as b', 'b.supplier_code','=','a.supplier_code')
					->where('author_id', '=', Auth::user()->author_id)
					->where(function ($query) use($request) {
							$query->where('purchase_date','like','%'.$request->search.'%')
								->orWhere('purchase_id', 'like','%'.$request->search.'%');
					})
					->orderBy('purchase_date')
					->paginate($this->paginateValue);

			return view('purchase_orders.index', [
					'purchase_orders'=>$purchase_orders,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$purchase_orders = DB::table('purchase_orders as a')
					->where('purchase_id','=',$id)
					->leftJoin('suppliers as b', 'b.supplier_code','=','a.supplier_code')
					->orderBy('purchase_date')
					->paginate($this->paginateValue);

			return view('purchase_orders.index', [
					'purchase_orders'=>$purchase_orders,
					'supplier' => Supplier::all()->sortBy('supplier_name')->lists('supplier_name', 'supplier_code')->prepend('',''),
			]);
	}

	public function post(Request $request)
	{
		$purchase_order = PurchaseOrder::findOrFail($request->purchase_id);
		
		return view('purchase_orders.post', [
			'purchase_order'=>$purchase_order
			]);
	}

	public function postVerify(Request $request)
	{
		$purchase_order = PurchaseOrder::findOrFail($request->purchase_id);
		$purchase_order->purchase_posted=1;
		$purchase_order->save();

		return redirect('/purchase_order_lines/index/'.$request->purchase_id);
	}

	public function diet($id)
	{
			Log::info("------------------------");
			$dietHelper = new DietHelper();
			$dt = Carbon::now();
			if (empty($request->dayOfWeek)) {
					$dayOfWeek = $dt->dayOfWeek;
			} else {
					$dayOfWeek = $request->dayOfWeek;
			}
			if (empty($request->weekOfMonth)) {
					$weekOfMonth = $dt->weekOfMonth;
			} else {
					$weekOfMonth = $request->weekOfMonth;
			}

			if ($weekOfMonth>4) $weekOfMonth=1;
					
			$diets = Diet::all();

			$po = [];
			foreach($diets as $diet) {
					Log::info("--".$diet->diet_code);
					$diet_classes = DietClass::where('diet_code', $diet->diet_code)->get();
					$products = DB::table('diet_menus as a')
									->select('a.product_code','a.period_code','c.period_position', 'product_name','period_name','a.class_code')
									->leftjoin('diet_classes as b', 'b.class_code','=','a.class_code')
									->leftjoin('diet_periods as c', 'c.period_code','=','a.period_code')
									->leftjoin('products as d', 'd.product_code','=','a.product_code')
									->where('b.diet_code','=', $diet->diet_code)
									->where('week_index','=', $weekOfMonth)
									->where('day_index','=', $dayOfWeek)
									->where('product_bom', '=', 1)
									->groupBy('a.product_code')
									->orderBy('period_position')
									->orderBy('product_name')
									->get();

					foreach ($products as $product) {
						$total=0;
						foreach ($diet_classes as $class) {
							$count=$dietHelper->cooklist($diet->diet_code,$class->class_code, $product->period_code, $product->product_code);
							$total+=$count;
						}
						$boms =$dietHelper->bom($product->product_code);
						
						foreach ($boms as $bom) {
							Log::info($bom->product->product_name);
							Log::info($bom->bom_quantity*$total);
							$value=0;
							if (!empty($po[$bom->product->product_code])) {
									$value = $po[$bom->product->product_code];
							}
							$po[$bom->product->product_code]=$bom->bom_quantity*$total + $value;
						}
					}
			}

			foreach($po as $key=>$value) {
					Log::info($key."==".$value);
					$product = Product::find($key);
					if ($product->product_purchased==1) {
							$purchase_order_line = new PurchaseOrderLine();
							$purchase_order_line->purchase_id = $id;
							$purchase_order_line->line_quantity_ordered = $value;
							$purchase_order_line->line_quantity_received = $value;
							$purchase_order_line->line_total = $value*$product->product_purchase_price;
							$purchase_order_line->product_code = $key;
							$purchase_order_line->line_price = $product->product_purchase_price;
							$purchase_order_line->save();
					}
			}

		return redirect('/purchase_order_lines/index/'.$id);
	}
}
