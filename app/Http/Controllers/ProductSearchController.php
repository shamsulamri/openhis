<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Input;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\ProductSearch;
use Log;
use DB;
use Session;
use App\ProductCategory as Category;
use App\ProductStatus as Status;
use App\UnitMeasure as Unit;
use App\QueueLocation as Location;
use App\Form;
use App\Product;
use App\BillMaterial;
use App\OrderSet;
use App\DietMenu;
use App\ProductAuthorization;
use Auth;
use App\StockInputLine;
use App\StockInput;
use App\TaxCode;
use App\Purchase;
use App\InventoryMovement;

class ProductSearchController extends Controller
{
	public $paginateValue=10;
	public $gline_id=0;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index(Request $request)
	{
			/*
			$product_searches = DB::table('products')
					->orderBy('product_name')
					->paginate($this->paginateValue);
			 */

			$product_searches = Product::orderBy('product_name')
					->leftjoin('product_categories as b', 'b.category_code','=', 'products.category_code');

			$product_authorization = ProductAuthorization::select('category_code')->where('author_id', Auth::user()->author_id);
			if (!$product_authorization->get()->isEmpty()) {
					$product_searches = $product_searches->whereIn('products.category_code',$product_authorization->pluck('category_code'));
			}


			$reason = $request->reason;
			$return_id = 0;

			$categories = $product_authorization->orderBy('category_name')
									->select('b.category_code','category_name')
									->leftjoin('product_categories as b', 'b.category_code','=', 'product_authorizations.category_code')
									->lists('category_name', 'b.category_code')
									->prepend('','');

			switch ($reason) {
					case "purchase":
							$return_id = $request->purchase_id;
							break;
					case "bom":
							$return_id = $request->product_code;
							break;
					case "asset":
							$return_id = $request->set_code;
							break;
					case "bulk":
							$return_id = $request->input_id;
							break;
			}

			$product_searches = $product_searches->paginate($this->paginateValue);
			
			if ($request->type == 'reorder') {
				$stores = Auth::user()->storeCodeInString();
				$sql = "
						select product_name, a.product_code, a.store_code, store_name, sum(inv_quantity) as stock_quantity, limit_min, limit_max, unit_name, unit_shortname, on_purchase
						from inventories as a
						left join stock_limits as b on (a.product_code = b.product_code and b.store_code = a.store_code)
						left join products as c on (c.product_code = a.product_code)
						left join stores as d on (d.store_code = a.store_code)
						left join ref_unit_measures as e on (e.unit_code = a.unit_code)
						left join (
								select a.product_code, sum(line_quantity) as on_purchase
								from purchase_lines as a
								left join purchases as b on (b.purchase_id = a.purchase_id)
								left join inventories as c on (c.line_id = a.line_id)
								where document_code = 'purchase_order'
								and purchase_posted = 1
								and inv_id is null
								group by a.product_code
						) as f on (f.product_code = a.product_code)
						where a.store_code in (". $stores .")
						group by a.product_code, a.store_code, limit_min, limit_max, unit_name, unit_shortname
						having stock_quantity<limit_min
				";

					$data = DB::select($sql);

					/** Pagination **/
					$page = Input::get('page', 1); 
					$offSet = ($page * $this->paginateValue) - $this->paginateValue;
					$itemsForCurrentPage = array_slice($data, $offSet, $this->paginateValue, true);

					$product_searches = new LengthAwarePaginator($itemsForCurrentPage, count($data), 
							$this->paginateValue, 
							$page, 
							['path' => $request->url(), 
							'query' => $request->query()]
					);
			}

			$purchase = null;
			if ($request->purchase_id) {
					$purchase = Purchase::find($request->purchase_id);
			}

			$movement = null;
			if ($request->move_id) {
					$movement = InventoryMovement::find($request->move_id);
			}

			return view('product_searches.index', [
					'product_searches'=>$product_searches,
					'purchase_id'=>$request->purchase_id,
					'line_id'=>$request->line_id,
					'product_code'=>$request->product_code,
					'set_code'=>$request->set_code,
					'reason'=>$reason,
					'return_id'=>$return_id,
					'class_code'=>$request->class_code,
					'period_code'=>$request->period_code,
					'week'=>$request->week,
					'day'=>$request->day,
					'diet_code'=>$request->diet_code,
					'input_id'=>$request->input_id,
					'purchase'=>$purchase,
					'move_id'=>$request->move_id,
					'movement'=>$movement,
					'type'=>$request->type,
			]);
	}

	public function create()
	{
			$product_search = new ProductSearch();
			return view('product_searches.create', [
					'product_search' => $product_search,
					'category' => Category::all()->sortBy('category_name')->lists('category_name', 'category_code')->prepend('',''),
					'unit' => Unit::all()->sortBy('unit_name')->lists('unit_name', 'unit_code')->prepend('',''),
					'location' => Location::all()->sortBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
					'form' => Form::all()->sortBy('form_name')->lists('form_name', 'form_code')->prepend('',''),
					'status' => Status::all()->sortBy('status_name')->lists('status_name', 'status_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$product_search = new ProductSearch();
			$valid = $product_search->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$product_search = new ProductSearch($request->all());
					$product_search->product_code = $request->product_code;
					$product_search->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/product_searches/id/'.$product_search->product_code);
			} else {
					return redirect('/product_searches/create')
							->withErrors($valid)
							->withInput();
			}
	}

	/**
	public function add($purchase_id, $product_code)
	{
			return "X";

			$default_tax = TaxCode::where('tax_default',1)->first();
			$product = Product::find($product_code);
			$purchase_order_line = new PurchaseOrderLine();
			$purchase_order_line->purchase_id = $purchase_id;
			$purchase_order_line->product_code = $product_code;
			$purchase_order_line->line_unit_price = $product->product_purchase_price;
			$purchase_order_line->line_quantity = 1;
			$purchase_order_line->unit_code = 'unit';
			if ($product->product_input_tax) {
				$purchase_order_line->tax_code = $product->product_input_tax;
				$purchase_order_line->tax_rate = isset($product->product_input_tax) ? $product->inputTax->tax_rate : 0;
			} else {
				$purchase_order_line->tax_code = $default_tax->tax_code;
				$purchase_order_line->tax_rate = isset($default_tax->tax_rate) ? $default_tax->tax_rate : 0;
			}
			if ($default_tax->tax_rate>0) {
				$purchase_order_line->line_subtotal_tax = $product->product_purchase_price*(1+$default_tax->tax_rate/100);
			}
			$purchase_order_line->save();

			$this->gline_id = $purchase_order_line->line_id;
			Session::flash('message', 'Record added successfully.');
			return redirect('/product_searches?reason=purchase_order&purchase_id='.$purchase_id.'&line_id='.$purchase_order_line->line_id);
	}
	**/

	public function bom($product_code, $bom_product_code) 
	{
			$bom = new BillMaterial();
			$bom->product_code = $product_code;
			$bom->bom_product_code = $bom_product_code;
			$bom->save();

			Session::flash('message', 'Record successfully created.');
			return redirect('/product_searches?reason=bom&product_code='.$product_code);
			
	}

	public function asset($set_code, $product_code)
	{
			$order_set = new OrderSet();
			$order_set->set_code = $set_code;
			$order_set->product_code = $product_code;
			$order_set->save();

			Session::flash('message', 'Record successfully created.');
			return redirect('/product_searches?reason=asset&set_code='.$set_code);
	}
			
	public function menu($class_code, $period_code, $week, $day, $product_code, $diet_code)
	{
			$diet_menu = new DietMenu();
			$diet_menu->diet_code = $diet_code;
			$diet_menu->class_code = $class_code;
			$diet_menu->period_code = $period_code;
			$diet_menu->product_code = $product_code;
			$diet_menu->week_index = $week;
			$diet_menu->day_index = $day;
			$diet_menu->save();

			Session::flash('message', 'Record successfully created.');
			return redirect('/product_searches?reason=menu&class_code='.$class_code.'&period_code='.$period_code.'&week='.$week.'&day='.$day.'&diet_code='.$diet_code);
	}

	public function edit($id) 
	{
			$product_search = ProductSearch::findOrFail($id);
			return view('product_searches.edit', [
					'product_search'=>$product_search,
					'category' => Category::all()->sortBy('category_name')->lists('category_name', 'category_code')->prepend('',''),
					'unit' => Unit::all()->sortBy('unit_name')->lists('unit_name', 'unit_code')->prepend('',''),
					'location' => Location::all()->sortBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
					'form' => Form::all()->sortBy('form_name')->lists('form_name', 'form_code')->prepend('',''),
					'status' => Status::all()->sortBy('status_name')->lists('status_name', 'status_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$product_search = ProductSearch::findOrFail($id);
			$product_search->fill($request->input());

			$product_search->product_unit_charge = $request->product_unit_charge ?: 0;

			$valid = $product_search->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$product_search->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/product_searches/id/'.$id);
			} else {
					return view('product_searches.edit', [
							'product_search'=>$product_search,
					'category' => Category::all()->sortBy('category_name')->lists('category_name', 'category_code')->prepend('',''),
					'unit' => Unit::all()->sortBy('unit_name')->lists('unit_name', 'unit_code')->prepend('',''),
					'location' => Location::all()->sortBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
					'form' => Form::all()->sortBy('form_name')->lists('form_name', 'form_code')->prepend('',''),
					'status' => Status::all()->sortBy('status_name')->lists('status_name', 'status_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$product_search = ProductSearch::findOrFail($id);
		return view('product_searches.destroy', [
			'product_search'=>$product_search
			]);

	}
	public function destroy($id)
	{	
			ProductSearch::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/product_searches');
	}
	
	public function search(Request $request)
	{
			$reason=$request->reason;
			$product_searches=null;
			$search = trim($request->search, " ");
			$product_authorization = ProductAuthorization::select('category_code')->where('author_id', Auth::user()->author_id);
			switch ($reason) {
					case "bom":
							$product_searches = Product::orderBy('product_name')
									->where('product_code','!=', $request->product_code)
									->where(function ($query) use($search) {
											$query->where('product_name','like','%'.$search.'%')
												  ->orWhere('product_code', 'like','%'.$search.'%')
												  ->orWhere('product_name_other','like','%'.$search.'%');
									});
							
							if (!$product_authorization->get()->isEmpty()) {
									$product_searches = $product_searches->whereIn('products.category_code',$product_authorization->pluck('category_code'));
							}
							$product_searches = $product_searches->paginate($this->paginateValue);
							break;
					default:
							$product_searches = Product::orderBy('product_name')
									->where(function ($query) use($search) {
											$query->where('product_name','like','%'.$search.'%')
												  ->orWhere('product_code', 'like','%'.$search.'%')
												  ->orWhere('product_name_other','like','%'.$search.'%');
									})
									->orderBy('product_name');

							if (!$product_authorization->get()->isEmpty()) {
									$product_searches = Product::orderBy('product_name')
											->whereIn('products.category_code',$product_authorization->pluck('category_code'))
											->where(function ($query) use($search) {
													$query->where('product_name','like','%'.$search.'%')
														  ->orWhere('product_code', 'like','%'.$search.'%')
												  		  ->orWhere('product_name_other','like','%'.$search.'%');
											})
									->orderBy('product_name');
							}

							$product_searches = $product_searches->paginate($this->paginateValue);
			}

			if ($product_searches->count()==1) {
					switch ($reason) {
							case "bulk";
									$this->bulk($request->input_id, $product_searches[0]->product_code);
									return redirect('/product_searches?reason=bulk&input_id='.$request->input_id);
									break;
							case "purchase";
									//$this->add($request->purchase_id, $product_searches[0]->product_code);
									//return redirect('/product_searches?reason=purchase_order&purchase_id='.$request->purchase_id.'&line_id='.$this->gline_id);
									return redirect('/purchase_lines/add/'.$request->purchase_id.'/'.$product_searches[0]->product_code);
									break;
							case "stock";
									return redirect('/inventory_movements/add/'.$request->move_id.'/'.$product_searches[0]->product_code);
									break;
							case "bom";
									$this->bom($request->product_code, $product_searches[0]->product_code);
									return redirect('/product_searches?reason=bom&product_code='.$request->product_code);
									break;
							case "asset";
									$this->asset($request->set_code, $product_searches[0]->product_code);
									return redirect('/product_searches?reason=asset&set_code='.$request->set_code);
									break;
					}
			} else {

					$purchase = null;
					if ($request->purchase_id) {
							$purchase = Purchase::find($request->purchase_id);
					}

					$movement = null;
					if ($request->move_id) {
							$movement = InventoryMovement::find($request->move_id);
					}
					return view('product_searches.index', [
							'product_searches'=>$product_searches,
							'search'=>$request->search,
							'purchase_id'=>$request->purchase_id,
							'reason'=>$request->reason,
							'product_code'=>$request->product_code,
							'set_code'=>$request->set_code,
							'return_id'=>$request->return_id,
							'class_code'=>$request->class_code,
							'period_code'=>$request->period_code,
							'week'=>$request->week,
							'day'=>$request->day,
							'diet_code'=>$request->diet_code,
							'line_id'=>$request->line_id,
							'input_id'=>$request->input_id,
							'move_id'=>$request->move_id,
							'movement'=>$movement,
							'purchase'=>$purchase,
							'type'=>$request->type,
					]);
			}
	}

	public function searchById($id)
	{
			$product_searches = DB::table('products')
					->where('product_code','=',$id)
					->paginate($this->paginateValue);

			return view('product_searches.index', [
					'product_searches'=>$product_searches
			]);
	}
}
