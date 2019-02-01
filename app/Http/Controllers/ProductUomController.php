<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\ProductUom;
use Log;
use DB;
use Session;
use App\Product;
use App\UnitMeasure as Unit;


class ProductUomController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$product_uoms = DB::table('product_uoms')
					->orderBy('product_code')
					->paginate($this->paginateValue);
			return view('product_uoms.index', [
					'product_uoms'=>$product_uoms
			]);
	}

	public function uom(Request $request) 
	{
			$product_uoms = ProductUom::where('product_code', '=', $request->id)
					->paginate($this->paginateValue);

			$product = Product::find($request->id);
			return view('product_uoms.index_uom', [
					'product_uoms'=>$product_uoms,
					'product'=>$product,
			]);
	}

	public function create(Request $request)
	{
			$product = Product::find($request->id);
			$product_uom = new ProductUom();

			return view('product_uoms.create', [
					'product_uom' => $product_uom,
					'product' => $product,
					'unit' => Unit::all()->sortBy('unit_name')->lists('unit_name', 'unit_code')->prepend('',''),
					]);
	}

	public function uncheckDefaultCost($product_code) {
			Log::info("Cost...");
			$uom = ProductUom::where('product_code', $product_code)
					->where('uom_default_cost', '=', 1)
					->update(['uom_default_cost'=>0]);
	}

	public function uncheckDefaultPrice($product_code) {
			Log::info("Price...");
			ProductUom::where('product_code', $product_code)
					->where('uom_default_price', '=', 1)
					->update(['uom_default_price'=>0]);
	}
	public function store(Request $request) 
	{
			$product_uom = new ProductUom();
			$valid = $product_uom->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$product_uom = new ProductUom($request->all());
					$product_uom->product_code = $request->product_code;
					$product_uom->uom_default_cost = $request->uom_default_cost ?: 0;
					if ($product_uom->uom_default_cost == 1) {
						$this->uncheckDefaultCost($product_uom->product_code);
					}
					$product_uom->uom_default_price = $request->uom_default_price ?: 0;
					if ($product_uom->uom_default_price == 1) {
						$this->uncheckDefaultPrice($product_uom->product_code);
					}
					$product_uom->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/product/uom/'.$product_uom->product_code);
			} else {
					return redirect('/product_uoms/create?id='.$request->product_code)
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$product_uom = ProductUom::findOrFail($id);

			return view('product_uoms.edit', [
					'product_uom'=>$product_uom,
					'product' => $product_uom->product,
					'unit' => Unit::all()->sortBy('unit_name')->lists('unit_name', 'unit_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$product_uom = ProductUom::findOrFail($id);
			if ($request->uom_default_cost == 1) {
				$this->uncheckDefaultCost($product_uom->product_code);
			}

			if ($request->uom_default_price == 1) {
				$this->uncheckDefaultPrice($product_uom->product_code);
			}

			$product_uom = ProductUom::findOrFail($id);
			$product_uom->fill($request->input());
			$product_uom->uom_default_cost = $request->uom_default_cost ?: 0;
			$product_uom->uom_default_price = $request->uom_default_price ?: 0;

			$valid = $product_uom->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$product_uom->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/product/uom/'.$product_uom->product_code);
			} else {
					return redirect('/product_uoms/'.$id.'/edit')
							->withErrors($valid)
							->withInput();
			}
	}
	
	public function delete($id)
	{
		$product_uom = ProductUom::findOrFail($id);
		return view('product_uoms.destroy', [
			'product_uom'=>$product_uom,
			'product'=>$product_uom->product,
			]);

	}
	public function destroy($id)
	{	
			$product_uom = ProductUom::find($id);
			ProductUom::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/product/uom/'.$product_uom->product_code);
	}
	
	public function search(Request $request)
	{
			$product_uoms = DB::table('product_uoms')
					->where('product_code','like','%'.$request->search.'%')
					->orWhere('id', 'like','%'.$request->search.'%')
					->orderBy('product_code')
					->paginate($this->paginateValue);

			return view('product_uoms.index', [
					'product_uoms'=>$product_uoms,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$product_uoms = ProductUom::where('id','=',$id)
					->paginate($this->paginateValue);

			return view('product_uoms.index', [
					'product_uoms'=>$product_uoms
			]);
	}
}
