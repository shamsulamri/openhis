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

class AssemblyController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index($id)
	{

			$bom = BillMaterial::where('product_code',$id)->pluck('bom_product_code');
			$products = Product::whereIn('products.product_code', $bom)
							->leftjoin('bill_materials', 'bom_product_code','=','products.product_code')
							->get();

			$product = Product::findOrFail($id);
			return view('assemblies.index', [
					'product'=>$product,
					'products'=>$products,
					'quantity'=>0,
					'store' => Store::all()->sortBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
					'store_code'=>'main',
			]);

	}

	public function build(Request $request, $id)
	{
			$bom = BillMaterial::where('product_code',$id)->pluck('bom_product_code');
			$products = Product::whereIn('products.product_code', $bom)
							->leftjoin('bill_materials', 'bom_product_code','=','products.product_code')
							->get();

			$flag=True;
			$quantity = $request->quantity;
			$product_controller = new ProductController();

			foreach($products as $product) {
					if ($product->product_on_hand<$product->bom_quantity*$quantity) $flag=False;
			}

			if (empty($request->store_code)) $flag=False;
			if ($flag) {
				foreach($products as $product) {
						/*
						$bom = Product::find($product->bom_product_code);
						$bom->product_on_hand = $bom->product_on_hand-($quantity*$product->bom_quantity);
						$bom->save();
						 */

						$stock = new Stock();
						$stock->move_code='adjust';
						$stock->store_code = $request->store_code;
						$stock->product_code = $product->bom_product_code;
						$stock->stock_quantity = -1*$quantity*$product->bom_quantity;
						$stock->stock_date = DojoUtility::now(); 
						$stock->stock_description = "Build Assembly: ".$id;
						$stock->save();

						$product_controller->updateTotalOnHand($stock->product_code);

				}

				$stock = new Stock();
				$stock->move_code='receive';
				$stock->store_code = $request->store_code;
				$stock->product_code = $id;
				$stock->stock_quantity = $quantity;
				$stock->stock_date = DojoUtility::now(); 
				$stock->stock_description = "Build Assembly";
				$stock->save();

				$product_controller->updateTotalOnHand($id);
				/*
				$product = Product::find($id);
				$product->product_on_hand = $product->product_on_hand + $quantity;
				Log::info($product);
				$product->save();
				 */

				Session::flash('message', 'Product built.');
				return redirect('/products/id/'.$id);
			} else {
					if (empty($request->store_code)) {
						Session::flash('message', 'Store not defined.');
					} else {
						Session::flash('message', 'Not enough item to build');
					}
					$product = Product::findOrFail($id);
					return view('assemblies.index', [
							'product'=>$product,
							'products'=>$products,
							'quantity'=>$request->quantity,
							'store' => Store::all()->sortBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
							'store_code'=>$request->store_code,
					]);
			}
				
	}

	public function dismantle($id)
	{
			$product = Product::find($id);

			return view('assemblies.dismantle', [
					'product'=>$product,
			]);
	}

	public function destroy(Request $request, $id)
	{
			$product_controller = new ProductController();
			$quantity = $request->quantity;
			$bom_products = BillMaterial::where('product_code',$id)->get();

			foreach ($bom_products as $bom_product) {

						$stock = new Stock();
						$stock->move_code='adjust';
						$stock->store_code = 'main';
						$stock->product_code = $bom_product->bom_product_code;
						$stock->stock_quantity = $quantity*$bom_product->bom_quantity;
						$stock->stock_date = DojoUtility::now(); 
						$stock->stock_description = "Dismantle Assembly: ".$id;
						$stock->save();

						$product_controller->updateTotalOnHand($stock->product_code);
			}

				$stock = new Stock();
				$stock->move_code='adjust';
				$stock->store_code = 'main';
				$stock->product_code = $id;
				$stock->stock_quantity = -1*$quantity;
				$stock->stock_date = DojoUtility::now(); 
				$stock->stock_description = "Dismantle Assembly: ".$quantity;
				$stock->save();

				$product_controller->updateTotalOnHand($id);
			Session::flash('message', 'Product dismantle.');
			return redirect('/products/id/'.$id);
	}
}
