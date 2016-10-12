<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
use App\PurchaseOrderLine;
use App\Product;
use App\BillMaterial;
use App\OrderSet;
use App\PurchaseOrder;
use App\DietMenu;
use App\ProductAuthorization;
use Auth;

class ProductSearchController extends Controller
{
	public $paginateValue=10;

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


			$purchase_order = PurchaseOrder::find($request->purchase_id);

			$reason = $request->reason;
			$return_id = 0;

			switch ($reason) {
					case "purchase_order":
							$product_searches = $product_searches->where('product_purchased','=',1);
							//dd($product_searches->toSql());
							$return_id = $request->purchase_id;
							break;
					case "bom":
							$return_id = $request->product_code;
							break;
					case "asset":
							$return_id = $request->set_code;
							break;
			}

			$product_searches = $product_searches->paginate($this->paginateValue);

			return view('product_searches.index', [
					'product_searches'=>$product_searches,
					'purchase_id'=>$request->purchase_id,
					'product_code'=>$request->product_code,
					'set_code'=>$request->set_code,
					'reason'=>$request->reason,
					'purchase_order'=>$purchase_order,
					'return_id'=>$return_id,
					'class_code'=>$request->class_code,
					'period_code'=>$request->period_code,
					'week'=>$request->week,
					'day'=>$request->day,
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

	public function add($purchase_id, $id)
	{
			$product = Product::find($id);
			$purchase_order_line = new PurchaseOrderLine();
			$purchase_order_line->purchase_id = $purchase_id;
			$purchase_order_line->product_code = $id;
			$purchase_order_line->line_price = $product->product_purchase_price;
			$purchase_order_line->save();
			Session::flash('message', 'Record successfully created.');
			return redirect('/product_searches?reason=purchase_order&purchase_id='.$purchase_id);
	}

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
			
	public function menu($class_code, $period_code, $week, $day, $product_code)
	{
			$diet_menu = new DietMenu();
			$diet_menu->class_code = $class_code;
			$diet_menu->period_code = $period_code;
			$diet_menu->product_code = $product_code;
			$diet_menu->week_index = $week;
			$diet_menu->day_index = $day;
			$diet_menu->save();

			Session::flash('message', 'Record successfully created.');
			return redirect('/product_searches?reason=menu&class_code='.$class_code.'&period_code='.$period_code.'&week='.$week.'&day='.$day);
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
			$product_search->product_purchased = $request->product_purchased ?: 0;
			$product_search->product_sold = $request->product_sold ?: 0;
			$product_search->product_bom = $request->product_bom ?: 0;

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
			$search = $request->search;
			$product_authorization = ProductAuthorization::select('category_code')->where('author_id', Auth::user()->author_id);
			switch ($reason) {
					case "bom":
							$product_searches = Product::orderBy('product_name')
									->where('product_code','!=', $request->product_code)
									->where('product_bom','!=',1)
									->where(function ($query) use($search) {
											$query->where('product_name','like','%'.$search.'%')
												  ->orWhere('product_code', 'like','%'.$search.'%');
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
												  ->orWhere('product_code', 'like','%'.$search.'%');
									})
									->orderBy('product_name');

							if (!$product_authorization->get()->isEmpty()) {
									$product_searches = Product::orderBy('product_name')
											->whereIn('products.category_code',$product_authorization->pluck('category_code'))
											->where(function ($query) use($search) {
													$query->where('product_name','like','%'.$search.'%')
														  ->orWhere('product_code', 'like','%'.$search.'%');
											})
									->orderBy('product_name');
							}

							if ($reason=='purchase_order') {
								$product_searches = $product_searches->where('product_purchased','=',1);
							}

							$product_searches = $product_searches->paginate($this->paginateValue);
			}

			$purchase_order = PurchaseOrder::find($request->purchase_id);

			return view('product_searches.index', [
					'product_searches'=>$product_searches,
					'search'=>$request->search,
					'purchase_id'=>$request->purchase_id,
					'reason'=>$request->reason,
					'product_code'=>$request->product_code,
					'set_code'=>$request->set_code,
					'purchase_order'=>$purchase_order,
					'return_id'=>$request->return_id,
					'class_code'=>$request->class_code,
					'period_code'=>$request->period_code,
					'week'=>$request->week,
					'day'=>$request->day,
					]);
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
