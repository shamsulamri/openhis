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
						$stock->move_code='adjust';
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

			return view('assemblies.explode', [
					'product'=>$product,
					'store'=>Auth::user()->storeList()->prepend('',''),
					'store_code'=>Auth::user()->defaultStore($request),
					'stock_helper'=>new StockHelper(),
			]);
	}

	public function destroy(Request $request, $id)
	{
			$product_controller = new ProductController();
			$quantity = $request->quantity;
			$bom_products = BillMaterial::where('bill_materials.product_code',$id)
					->leftjoin('products as b','b.product_code', '=', 'bill_materials.bom_product_code')
					->where('product_dismantle_material','=',1)
					->get();
			$product = Product::find($id);

			$stock_helper = new StockHelper();
			$on_hand = $stock_helper->getStockCountByStore($product->product_code, $request->store_code);
			if ($quantity>$on_hand) {
					Session::flash('message', 'Quantity more than on hand.');
					return redirect('/explode_assembly/'.$id)
							->withInput();
			}


			$stock = new Stock();
			$stock->move_code='explode';
			$stock->store_code = $request->store_code;
			$stock->product_code = $id;
			$stock->stock_quantity = -1*$quantity;
			$stock->stock_datetime = DojoUtility::now(); 
			$stock->stock_description = "Explode Assembly: ".$quantity;

			$product = Product::find($id);
			$stock->stock_value = $product->product_average_cost*$stock->stock_quantity;

			$stock->save();
			$stock_id = $stock->stock_id;

			foreach ($bom_products as $bom_product) {

						$stock = new Stock();
						$stock->move_code='adjust';
						$stock->store_code = $request->store_code;
						$stock->product_code = $bom_product->bom_product_code;
						$stock->stock_quantity = $quantity*$bom_product->bom_quantity;
						$stock->stock_datetime = DojoUtility::now(); 
						$stock->stock_description = "Explode Assembly: ".$id;
						$stock->username = Auth::user()->username;
						$stock->stock_tag = $stock_id;

						$product = Product::find($bom_product->bom_product_code);
						$stock->stock_value = $product->product_average_cost*$stock->stock_quantity;
						
						$stock->save();

						$product_controller->updateTotalOnHand($stock->product_code);
			}


				$product_controller->updateTotalOnHand($id);
			Session::flash('message', 'Product exploded.');
			return redirect('/explode_assembly/'.$id);
	}
}
