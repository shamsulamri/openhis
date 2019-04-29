<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\BillMaterial;
use App\Product;
use App\Store;
use App\Stock;
use Log;
use DB;
use Session;
use Gate;
use App\DojoUtility;
use App\Loan;
use Auth;
use App\StockHelper;
use App\StoreAuthorization;
use App\Inventory;
use App\ProductUom;

class AssemblyController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index(Request $request, $id)
	{
			$bom = BillMaterial::where('product_code',$id)->pluck('bom_product_code');
			/*
			$products = Product::whereIn('products.product_code', $bom)
							->leftjoin('bill_materials', 'bom_product_code','=','products.product_code')
							->get();
			 */

			$boms = BillMaterial::where('product_code', '=', $id)->get();
			$product = Product::findOrFail($id);

			$quantity = Loan::where('item_code', $id)
							->where('loan_code','request')
							->sum('loan_quantity');

			$store_code = ($request->store_code) ? $request->store_code : Auth::user()->authorization->store_code;

			return view('assemblies.index', [
					'product'=>$product,
					'boms'=>$boms,
					'quantity'=>$quantity,
					'store'=>Auth::user()->storeList()->prepend('',''),
					'store_code'=>$store_code,
					'stock_helper'=>new StockHelper(),
			]);

	}

	public function build(Request $request, $id)
	{
			$bom = BillMaterial::where('product_code',$id)->pluck('bom_product_code');
			/*
			$boms = Product::whereIn('products.product_code', $bom)
							->leftjoin('bill_materials', 'bom_product_code','=','products.product_code')
							->get();
			 */

			$boms = BillMaterial::where('product_code', '=', $id)->get();

			$flag=True;
			$quantity = $request->quantity;
			$product_controller = new ProductController();
			$max = $request->max;
			$msg="";

			$helper = new StockHelper();

			foreach($boms as $bom) {
					
					$on_hand = $helper->getStockOnHand($bom->bom_product_code, $request->store_code);

					//if ($bom->product->product_on_hand<$bom->bom_quantity*$quantity) $flag=False;
					if ($on_hand<$bom->bom_quantity*$quantity) $flag=False;
			}

			if (empty($request->store_code)) {
					$flag=False;
					$msg = "Store not selected"; 
			}
			if ($quantity<0) {
					$flag=False;
					$msg = "Build count error"; 
			}

			if ($quantity>$max) {
					$flag=False;
					if ($max>0) {
						$msg = "Build cannot be greater than ".$max;
					} else {
						$msg = "Build not possible.";
					}
			}

			if ($flag) {

				$product = Product::find($id);

				$inventory = new Inventory();
				$inventory->move_code = 'stock_adjust';
				$inventory->move_description = 'build';
				$inventory->store_code = $request->store_code;
				$inventory->product_code = $product->product_code;
				$inventory->inv_book_quantity = $helper->getStockOnHand($product->product_code, $request->store_code);
				$inventory->inv_physical_quantity = $quantity;
				$inventory->unit_code = $product->unit_code;

				$uom = ProductUom::where('product_code', $inventory->product_code)
						->where('unit_code', $inventory->unit_code)
						->first();

				$inventory->uom_rate =  $uom->uom_rate;
				$inventory->inv_unit_cost =  $uom->uom_cost;
				$inventory->inv_quantity = $quantity*$uom->uom_rate;
				$inventory->inv_physical_quantity = $quantity;
				$inventory->inv_subtotal =  $uom->uom_cost*$inventory->inv_physical_quantity;
				$inventory->inv_posted = 1;

				/*
				$inventory->uom_rate = $product->unit()->uom_rate;
				$inventory->inv_unit_cost = $product->unit()->uom_cost;
				$inventory->inv_posted = 1;
				$inventory->inv_quantity = $quantity;
				$inventory->inv_subtotal = $inventory->inv_unit_cost*$inventory->inv_quantity;
				 */
				$inventory->save();

				foreach($boms as $bom) {
						$product = Product::find($bom->bom_product_code);
						$inventory = new Inventory();
						$inventory->move_code = 'stock_adjust';
						$inventory->move_description = 'build';
						$inventory->store_code = $request->store_code;
						$inventory->product_code = $product->product_code;
						$inventory->inv_book_quantity = $helper->getStockOnHand($product->product_code, $request->store_code);
						$inventory->inv_physical_quantity = $quantity*$bom->bom_quantity;
						$inventory->unit_code = $product->unit_code;

						$uom = ProductUom::where('product_code', $inventory->product_code)
								->where('unit_code', $inventory->unit_code)
								->first();

						$inventory->uom_rate = $uom->uom_rate;
						$inventory->inv_unit_cost = $uom->uom_cost;
						$inventory->inv_posted = 1;
						$inventory->inv_quantity = -$inventory->inv_physical_quantity;
						$inventory->inv_subtotal =  $uom->uom_cost*$inventory->inv_quantity;
						$inventory->save();

				}

				Session::flash('message', 'Product built.');
				return redirect('/build_assembly/'.$id);
			} else {
					if (empty($request->store_code)) {
						Session::flash('message', 'Store not defined.');
					} else {
						Session::flash('message', 'Not enough item to build');
					}
					Session::flash('message', $msg);
					$product = Product::findOrFail($id);

					$stores = StoreAuthorization::where('author_id', Auth::user()->author_id)
							->select('store_name', 'store_authorizations.store_code')
							->leftjoin('stores as b', 'b.store_code','=', 'store_authorizations.store_code')
							->orderBy('store_name')
							->lists('store_name', 'store_code');

					return view('assemblies.index', [
							'product'=>$product,
							'boms'=>$boms,
							'quantity'=>$request->quantity,
							'store'=>Auth::user()->storeList()->prepend('',''),
							'store_code'=>$request->store_code,
							'stock_helper'=>new StockHelper(),
					]);
			}
				
	}

	public function build2(Request $request, $id)
	{
			$bom = BillMaterial::where('product_code',$id)->pluck('bom_product_code');
			/*
			$boms = Product::whereIn('products.product_code', $bom)
							->leftjoin('bill_materials', 'bom_product_code','=','products.product_code')
							->get();
			 */

			$boms = BillMaterial::where('product_code', '=', $id)->get();

			$flag=True;
			$quantity = $request->quantity;
			$product_controller = new ProductController();
			$max = $request->max;
			$msg="";

			foreach($boms as $bom) {
					if ($bom->product->product_on_hand<$bom->bom_quantity*$quantity) $flag=False;
			}

			if (empty($request->store_code)) {
					$flag=False;
					$msg = "Store not selected"; 
			}
			if ($quantity<0) {
					$flag=False;
					$msg = "Build count error"; 
			}

			if ($quantity>$max) {
					$flag=False;
					$msg = "Build cannot be greater than ".$max;
			}
			if ($flag) {

				$stock = new Stock();
				$stock->move_code='build';
				$stock->store_code = $request->store_code;
				$stock->product_code = $id;
				$stock->stock_quantity = $stock->stock_quantity+$quantity;
				$stock->stock_datetime = DojoUtility::now(); 
				$stock->stock_description = "Build Assembly";

				$product = Product::find($id);
				$stock->stock_value = $product->product_average_cost*$stock->stock_quantity;

				$stock->save();
				$stock_id = $stock->stock_id;

				foreach($boms as $bom) {
						$stock = new Stock();
						$stock->move_code='stock_adjust';
						$stock->store_code = $request->store_code;
						$stock->product_code = $bom->bom_product_code;
						$stock->stock_quantity = -1*$quantity*$bom->bom_quantity;
						$stock->stock_datetime = DojoUtility::now(); 
						$stock->stock_description = "Build Assembly: ".$id;
						$stock->username = Auth::user()->username;
						$stock->stock_tag = $stock_id;

						$product = Product::find($bom->bom_product_code);
						$stock->stock_value = $product->product_average_cost*$stock->stock_quantity;

						$stock->save();

						$product_controller->updateTotalOnHand($stock->product_code);

				}


				$product_controller->updateTotalOnHand($id);
				Session::flash('message', 'Product built.');
				return redirect('/build_assembly/'.$id);
			} else {
					if (empty($request->store_code)) {
						Session::flash('message', 'Store not defined.');
					} else {
						Session::flash('message', 'Not enough item to build');
					}
					Session::flash('message', $msg);
					$product = Product::findOrFail($id);

					$stores = StoreAuthorization::where('author_id', Auth::user()->author_id)
							->select('store_name', 'store_authorizations.store_code')
							->leftjoin('stores as b', 'b.store_code','=', 'store_authorizations.store_code')
							->orderBy('store_name')
							->lists('store_name', 'store_code');

					return view('assemblies.index', [
							'product'=>$product,
							'boms'=>$boms,
							'quantity'=>$request->quantity,
							'store' => $stores,
							'store_code'=>$request->store_code,
							'stock_helper'=>new StockHelper(),
					]);
			}
				
	}

	public function explode(Request $request, $id)
	{
			$product = Product::find($id);
			if ($request->store_code) {
					$store_code = $request->store_code;
			} else {
					$store_code = Auth::user()->defaultStore($request);
			}

			$helper = new StockHelper();
			$on_hand = $helper->getStockOnHand($id, $store_code);

			$boms = BillMaterial::where('product_code', '=', $id)->get();

			return view('assemblies.explode', [
					'product'=>$product,
					'store'=>Auth::user()->storeList(),
					'stock_helper'=>new StockHelper(),
					'store_code'=>$request->store_code?:$store_code,
					'on_hand'=>$on_hand,
					'boms'=>$boms,
			]);
	}

	public function destroy(Request $request, $id)
	{
			$product_controller = new ProductController();
			$quantity = $request->quantity;
			$bom_products = BillMaterial::where('bill_materials.product_code',$id)
					->leftjoin('products as b','b.product_code', '=', 'bill_materials.bom_product_code')
					->get();
			$product = Product::find($id);

			$helper = new StockHelper();
			$on_hand = $helper->getStockOnHand($product->product_code, $request->store_code);
			if ($quantity>$on_hand) {
					Session::flash('message', 'Quantity more than on hand.');
					return redirect('/explode_assembly/'.$id)
							->withInput();
			}

				$inventory = new Inventory();
				$inventory->move_code = 'stock_adjust';
				$inventory->move_description = 'explode';
				$inventory->store_code = $request->store_code;
				$inventory->product_code = $product->product_code;
				$inventory->inv_book_quantity = $helper->getStockOnHand($product->product_code, $request->store_code);
				$inventory->inv_physical_quantity = $quantity;
				$inventory->unit_code = $product->unit_code;

				$uom = ProductUom::where('product_code', $inventory->product_code)
						->where('unit_code', $inventory->unit_code)
						->first();

				$inventory->uom_rate =  $uom->uom_rate;
				$inventory->inv_unit_cost =  $uom->uom_cost;
				$inventory->inv_quantity = -($quantity*$uom->uom_rate);
				$inventory->inv_subtotal = $uom->uom_cost*$inventory->inv_quantity;
				$inventory->inv_posted = 1;
				$inventory->save();

			foreach ($bom_products as $bom_product) {
						$product = Product::find($bom_product->bom_product_code);
						$inventory = new Inventory();
						$inventory->move_code = 'stock_adjust';
						$inventory->move_description = 'explode';
						$inventory->store_code = $request->store_code;
						$inventory->product_code = $product->product_code;
						$inventory->inv_book_quantity = $helper->getStockOnHand($product->product_code, $request->store_code);
						$inventory->inv_physical_quantity = $bom_product->bom_quantity*$quantity;
						$inventory->unit_code = $product->unit_code;

						$uom = ProductUom::where('product_code', $inventory->product_code)
								->where('unit_code', $inventory->unit_code)
								->first();

						$inventory->uom_rate =  $uom->uom_rate;
						$inventory->inv_unit_cost =  $uom->uom_cost;
						$inventory->inv_quantity = $inventory->inv_physical_quantity*$uom->uom_rate;
						$inventory->inv_subtotal =  $uom->uom_cost*$inventory->inv_physical_quantity;
						$inventory->inv_posted = 1;

						$inventory->save();
			}

			Session::flash('message', 'Product exploded.');
			return redirect('/explode_assembly/'.$id);
	}
}
