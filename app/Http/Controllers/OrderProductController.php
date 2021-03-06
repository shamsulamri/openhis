<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\OrderProduct;
use Log;
use DB;
use Session;
use App\Category;
use App\Unit;
use App\Location;
use App\Form;
use App\Consultation;
use App\Set;
use App\OrderSet;
use App\DojoUtility;
use App\ProductCategory;
use App\Product;
use App\ProductAuthorization;
use App\OrderHelper;
use Auth;
use Gate;
use App\StockHelper;
use App\Encounter;
use App\Order;

class OrderProductController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			/*
			if (Gate::allows('module-consultation')) {
					return redirect('/order_product/drug');
			}
			 */

		 	$order_products = NULL; 	
			$favorites = DB::table('orders')
							->select('product_code', DB::raw('count(*) as total'))
							->where('user_id', Auth::user()->id)
							->groupBy('product_code')
							->orderBy('total', 'desc')
							->limit(20)
							->pluck('product_code');

			Log::info($favorites);
			$order_products = Product::whereIn('products.product_code', $favorites)
					->join(DB::raw('(select count(*) as total, product_code from orders where user_id='.Auth::user()->id.' group by product_code having total>1 limit 20) totalOrders'), function($join)
					{
							$join->on('products.product_code', '=', 'totalOrders.product_code');
					})
					->orderBy('total', 'desc')
									->paginate($this->paginateValue);

			$consultation = Consultation::findOrFail(Session::get('consultation_id'));

			$sets = Set::orderBy('set_name')
						->whereNull('user_id')
						->orWhere('user_id', '=', Auth::user()->id)
						->lists('set_name', 'set_code')
						->prepend('','');

			return view('order_products.index', [
					'order_products'=>$order_products,
					'consultation'=>$consultation,
					'patient'=>$consultation->encounter->patient,
					'tab'=>'order',
					'consultOption' => 'consultation',
					'sets' => $sets,
					'set_value' => '',
					'search' => '',
					'categories' => $this->getCategories(),
					'category_code'=> NULL,
					'negetive_stock'=>env('ALLOW_NEGETIVE_STOCK')?:0,
					'page' => NULL,
			]);
	}

	public function create()
	{
			$order_product = new OrderProduct();
			return view('order_products.create', [
					'order_product' => $order_product,
					'category' => Category::all()->sortBy('category_name')->lists('category_name', 'category_code')->prepend('',''),
					'unit' => Unit::all()->sortBy('unit_name')->lists('unit_name', 'unit_code')->prepend('',''),
					'location' => Location::all()->sortBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
					'form' => Form::all()->sortBy('form_name')->lists('form_name', 'form_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$order_product = new OrderProduct();
			$valid = $order_product->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$order_product = new OrderProduct($request->all());
					$order_product->product_code = $request->product_code;
					$order_product->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/order_products/id/'.$order_product->product_code);
			} else {
					return redirect('/order_products/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$order_product = OrderProduct::findOrFail($id);
			return view('order_products.edit', [
					'order_product'=>$order_product,
					'category' => Category::all()->sortBy('category_name')->lists('category_name', 'category_code')->prepend('',''),
					'unit' => Unit::all()->sortBy('unit_name')->lists('unit_name', 'unit_code')->prepend('',''),
					'location' => Location::all()->sortBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
					'form' => Form::all()->sortBy('form_name')->lists('form_name', 'form_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$order_product = OrderProduct::findOrFail($id);
			$order_product->fill($request->input());

			$order_product->product_active = $request->product_active ?: 0;
			$order_product->product_drop_shipment = $request->product_drop_shipment ?: 0;
			$order_product->product_stocked = $request->product_stocked ?: 0;
			$order_product->product_discontinued = $request->product_discontinued ?: 0;

			$valid = $order_product->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$order_product->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/order_products/id/'.$id);
			} else {
					return view('order_products.edit', [
							'order_product'=>$order_product,
					'category' => Category::all()->sortBy('category_name')->lists('category_name', 'category_code')->prepend('',''),
					'unit' => Unit::all()->sortBy('unit_name')->lists('unit_name', 'unit_code')->prepend('',''),
					'location' => Location::all()->sortBy('location_name')->lists('location_name', 'location_code')->prepend('',''),
					'form' => Form::all()->sortBy('form_name')->lists('form_name', 'form_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$order_product = OrderProduct::findOrFail($id);
		return view('order_products.destroy', [
			'order_product'=>$order_product
			]);

	}
	public function destroy($id)
	{	
			OrderProduct::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/order_products');
	}
	
	public function search(Request $request)
	{
			if (empty($request->search)) {
					if ($request->set_code=='drug_history') {
						$this->drugHistory($request);
						//return redirect('/order_product/drug');
					}
			}

			$categoryCodes = Auth::user()->categoryCodes();

			if (!empty($request->search)) {
				$searches = explode(";",$request->search);
				$order_bulk = False;
				foreach($searches as $search) {
						if (!empty($search)) {
								$product = Product::where('product_code',$search)
												->where('status_code', 'active')
												->whereIn('category_code',$categoryCodes)
												->first();

								if ($product) {
										if ($product->product_drop_charge == 1) {

												$response=0;
												if ($product->product_stocked==1) {

														if ($request->encounter_id) {
																$encounter = Encounter::find($request->encounter_id);
																if ($encounter->admission) {

																}
														}
														
														$stock_helper = new StockHelper();
														//$store_code = OrderHelper::getStoreAffected($product);
														$store_code = OrderHelper::getTargetStore($product);

														$allocated = $stock_helper->getStockAllocated($product->product_code, $store_code);
														$on_hand = $stock_helper->getStockOnHand($product->product_code, $store_code);




														//if ($on_hand-$allocated>0) {
																$response = OrderHelper::orderItem($product, $request->cookie('ward'));
														//}
												} else {
														$response = OrderHelper::orderItem($product, $request->cookie('ward'));
												}

												if ($response>0) {
														$order_bulk = True;
												}

										}
								}
						}
				}
				if ($order_bulk == True) {
						return redirect('/order_product/search');
				}

				$fields = explode(' ', $request->search);


				$order_products = Product::orderBy('product_name')
						->where('status_code', '=', 'active')
						->where(function ($query) use ($fields, $request) {
							foreach($fields as $field) {
								$query->where('product_name','like','%'.$field.'%')
									->orWhere('product_name_other','like','%'.$field.'%')
									->orWhere('product_code','like','%'.$field.'%');
							}
						});
				/*
						->orWhere(function ($query) use ($fields, $request) {
							foreach($fields as $field) {
								$query->where('product_name_other','like','%'.$field.'%');
								if (!empty($request->categories)) {
										$query->where('category_code','=', $request->categories);
								}
							}
						});
				 */

				if (empty($request->categories)) {
						if (count($categoryCodes)>0) {
								$order_products = $order_products->whereIn('category_code',$categoryCodes);
						}
				} else {
						$order_products = $order_products->where('category_code','=', $request->categories);
				}

				$order_products = $order_products->paginate($this->paginateValue);

			} else {

				$orderSets = DB::table('order_sets')
							->select('order_sets.product_code')
							->leftjoin('products as b', 'b.product_code', '=', 'order_sets.product_code')
							->where('status_code', '=', 'active')
							->where('set_code','=',$request->set_code)
							->pluck('product_code');

				if (!empty($request->categories)) {
						$order_products = Product::where('category_code',$request->categories)
							->where('status_code', '=', 'active')
							->orderBy('product_name')
							->paginate($this->paginateValue);
				} else {
						$order_products = Product::whereIn('product_code', $orderSets)
							->where('status_code', '=', 'active')
							->orderBy('product_name')
							->paginate($this->paginateValue);
				}

			}

			$consultation_id = Session::get('consultation_id'); //$request->consultation_id;
			$consultation = Consultation::findOrFail($consultation_id);

			$order_id=0;
			if (!empty($request->order_id)) {
				$order_id = $request->order_id;
			}

			$sets = Set::orderBy('set_name')
						->whereNull('user_id')
						->orWhere('user_id', '=', Auth::user()->id)
						->lists('set_name', 'set_code')
						->prepend('','');

			return view('order_products.index', [
					'order_products'=>$order_products,
					'search'=>$request->search,
					'consultation'=>$consultation,
					'patient'=>$consultation->encounter->patient,
					'tab'=>'order',
					'consultOption' => 'consultation',
					'sets' => $sets,
					'set_value' => $request->set_code,
					'page' => $request->page,
					'categories' => $this->getCategories(),
					'category_code'=> $request->categories,
					'order_id'=>$order_id,
					'negetive_stock'=>env('ALLOW_NEGETIVE_STOCK')?:0,
					]);
	}

	public function drugHistory(Request $request)
	{
			return $request->set_code;
			$consultation_id = Session::get('consultation_id'); //$request->consultation_id;
			$consultation = Consultation::findOrFail($consultation_id);

			$order_products = DB::table('orders as a')
					->select('a.order_id','a.product_code', 'product_name', 'b.created_at', 'drug_strength', 'unit_name', 'drug_dosage', 'dosage_name', 'route_name', 'frequency_name','drug_duration', 'period_name', 'product_stocked', 'product_on_hand')
					->leftJoin('encounters as b', 'a.encounter_id','=', 'b.encounter_id')
					->leftJoin('products as c', 'c.product_code', '=', 'a.product_code')
					->leftJoin('order_drugs as d', 'd.order_id', '=', 'a.order_id')
					->leftJoin('drug_dosages as e', 'e.dosage_code', '=', 'd.dosage_code')
					->leftJoin('drug_routes as f', 'f.route_code', '=', 'd.route_code')
					->leftJoin('drug_frequencies as g', 'g.frequency_code', '=', 'd.frequency_code')
					->leftJoin('ref_periods as h', 'h.period_code', '=', 'd.period_code')
					->leftJoin('ref_unit_measures as i', 'i.unit_code', '=', 'd.unit_code')
					->where('patient_id',$consultation->encounter->patient->patient_id)
					->where('category_code','drugs')
					->orderBy('order_id','desc')
					->paginate($this->paginateValue);
					

			return view('order_products.index', [
					'order_products'=>$order_products,
					'search'=>$request->search,
					'consultation'=>$consultation,
					'patient'=>$consultation->encounter->patient,
					'tab'=>'drug',
					'consultOption' => 'consultation',
					'sets' => Set::all()->sortBy('set_name')->lists('set_name', 'set_code')->prepend('Drug History','drug_history')->prepend('',''),
					'categories' => $this->getCategories(),
					'category_code'=> null,
					'set_value' => $request->set_code,
					'page' => $request->page,
					'dojo' => new DojoUtility(),
					'order_id'=>0,
					]);
	}

	public function searchById($id)
	{
			$order_products = DB::table('products')
					->where('product_code','=',$id)
					->paginate($this->paginateValue);

			return view('order_products.index', [
					'order_products'=>$order_products
			]);
	}

	public function getCategories() {
			$categories = ProductCategory::all()->sortBy('category_name')->lists('category_name', 'category_code')->prepend('','');
			$product_authorization = ProductAuthorization::select('category_code')->where('author_id', Auth::user()->author_id);

			if (!$product_authorization->get()->isEmpty()) {
				$categories = ProductCategory::whereIn('category_code', $product_authorization->pluck('category_code'))
								->where('category_code', '<>', 'drugs')
								->where('category_code', '<>', 'fee_procedure')
								->orderBy('category_name')
								->lists('category_name', 'category_code')
								->prepend('','');
			}

			Log::info($categories);
			return $categories;
	}
}
