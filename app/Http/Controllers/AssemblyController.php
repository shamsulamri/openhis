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

			return view('assemblies.index', [
					'product'=>$product,
					'boms'=>$boms,
					'quantity'=>$quantity,
					'store' => Store::all()->sortBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
					'store_code'=>'main',
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
				foreach($boms as $bom) {
						$stock = new Stock();
						$stock->move_code='adjust';
						$stock->store_code = $request->store_code;
						$stock->product_code = $bom->bom_product_code;
						$stock->stock_quantity = -1*$quantity*$bom->bom_quantity;
						$stock->stock_date = DojoUtility::now(); 
						$stock->stock_description = "Build Assembly: ".$id;
						$stock->username = Auth::user()->username;
						$stock->save();

						$product_controller->updateTotalOnHand($stock->product_code);

				}

				$stock = new Stock();
				$stock->move_code='receive';
				$stock->store_code = $request->store_code;
				$stock->product_code = $id;
				$stock->stock_quantity = $stock->stock_quantity+$quantity;
				$stock->stock_date = DojoUtility::now(); 
				$stock->stock_description = "Build Assembly";
				$stock->save();

				$product_controller->updateTotalOnHand($id);
				Session::flash('message', 'Product built.');
				return redirect('/products/id/'.$id);
			} else {
					if (empty($request->store_code)) {
						Session::flash('message', 'Store not defined.');
					} else {
						Session::flash('message', 'Not enough item to build');
					}
					Session::flash('message', $msg);
					$product = Product::findOrFail($id);
					return view('assemblies.index', [
							'product'=>$product,
							'boms'=>$boms,
							'quantity'=>$request->quantity,
							'store' => Store::all()->sortBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
							'store_code'=>$request->store_code,
					]);
			}
				
	}

	public function explode($id)
	{
			$product = Product::find($id);

			return view('assemblies.explode', [
					'product'=>$product,
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

			if ($quantity>$product->product_on_hand) {
					Session::flash('message', 'Quantity more than on hand.');
					return view('assemblies.explode', [
							'product'=>$product,
					]);
			}


			foreach ($bom_products as $bom_product) {

						$stock = new Stock();
						$stock->move_code='adjust';
						$stock->store_code = 'main';
						$stock->product_code = $bom_product->bom_product_code;
						$stock->stock_quantity = $quantity*$bom_product->bom_quantity;
						$stock->stock_date = DojoUtility::now(); 
						$stock->stock_description = "Dismantle Assembly: ".$id;
						$stock->username = Auth::user()->username;
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
			Session::flash('message', 'Product exploded.');
			return redirect('/products/id/'.$id);
	}
}
