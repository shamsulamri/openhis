<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\ProductPriceTier;
use Log;
use DB;
use Session;
use App\ProductCharge;


class ProductPriceTierController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$product_price_tiers = ProductPriceTier::orderBy('charge_code')
					->paginate($this->paginateValue);
			return view('product_price_tiers.index', [
					'product_price_tiers'=>$product_price_tiers
			]);
	}

	public function create()
	{
			$product_price_tier = new ProductPriceTier();
			return view('product_price_tiers.create', [
					'product_price_tier' => $product_price_tier,
					'charge' => ProductCharge::all()->sortBy('charge_name')->lists('charge_name', 'charge_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$product_price_tier = new ProductPriceTier();
			$valid = $product_price_tier->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$product_price_tier = new ProductPriceTier($request->all());
					$product_price_tier->tier_id = $request->tier_id;
					$product_price_tier->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/product_price_tiers/id/'.$product_price_tier->tier_id);
			} else {
					return redirect('/product_price_tiers/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$product_price_tier = ProductPriceTier::findOrFail($id);
			return view('product_price_tiers.edit', [
					'product_price_tier'=>$product_price_tier,
					'charge' => ProductCharge::all()->sortBy('charge_name')->lists('charge_name', 'charge_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$product_price_tier = ProductPriceTier::findOrFail($id);
			$product_price_tier->fill($request->input());


			$valid = $product_price_tier->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$product_price_tier->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/product_price_tiers/id/'.$id);
			} else {
					return view('product_price_tiers.edit', [
							'product_price_tier'=>$product_price_tier,
					'charge' => ProductCharge::all()->sortBy('charge_name')->lists('charge_name', 'charge_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$product_price_tier = ProductPriceTier::findOrFail($id);
		return view('product_price_tiers.destroy', [
			'product_price_tier'=>$product_price_tier
			]);

	}
	public function destroy($id)
	{	
			ProductPriceTier::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/product_price_tiers');
	}
	
	public function search(Request $request)
	{
			$product_price_tiers = ProductPriceTier::where('product_price_tiers.charge_code','like','%'.$request->search.'%')
					->leftJoin('product_charges as b', 'b.charge_code', '=', 'product_price_tiers.charge_code')
					->orWhere('charge_name', 'like','%'.$request->search.'%')
					->orderBy('tier_id')
					->paginate($this->paginateValue);

			return view('product_price_tiers.index', [
					'product_price_tiers'=>$product_price_tiers,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$product_price_tiers = ProductPriceTier::where('tier_id','=',$id)
					->paginate($this->paginateValue);

			return view('product_price_tiers.index', [
					'product_price_tiers'=>$product_price_tiers
			]);
	}
}
