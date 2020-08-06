<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DiscountRule;
use Log;
use DB;
use Session;
use App\Sponsor;
use App\Product;
use App\ProductCategory;
use App\ProductCategoryParent;


class DiscountRuleController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$discount_rules = DiscountRule::orderBy('sponsor_code')
					->paginate($this->paginateValue);

			return view('discount_rules.index', [
					'discount_rules'=>$discount_rules
			]);
	}

	public function create()
	{
			$discount_rule = new DiscountRule();
			return view('discount_rules.create', [
					'discount_rule' => $discount_rule,
					'sponsor' => Sponsor::all()->sortBy('sponsor_name')->lists('sponsor_name', 'sponsor_code')->prepend('',''),
					'product' => Product::all()->sortBy('product_name')->lists('product_name', 'product_code')->prepend('',''),
					'category' => ProductCategory::all()->sortBy('category_name')->lists('category_name', 'category_code')->prepend('',''),
					'parent' => ProductCategoryParent::all()->sortBy('parent_name')->lists('parent_name', 'parent_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$discount_rule = new DiscountRule();
			$valid = $discount_rule->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$discount_rule = new DiscountRule($request->all());
					$discount_rule->rule_id = $request->rule_id;
					$discount_rule->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/discount_rules/id/'.$discount_rule->rule_id);
			} else {
					return redirect('/discount_rules/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$discount_rule = DiscountRule::findOrFail($id);
			return view('discount_rules.edit', [
					'discount_rule'=>$discount_rule,
					'sponsor' => Sponsor::all()->sortBy('sponsor_name')->lists('sponsor_name', 'sponsor_code')->prepend('',''),
					'product' => Product::all()->sortBy('product_name')->lists('product_name', 'product_code')->prepend('',''),
					'category' => ProductCategory::all()->sortBy('category_name')->lists('category_name', 'category_code')->prepend('',''),
					'parent' => ProductCategoryParent::all()->sortBy('parent_name')->lists('parent_name', 'parent_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$discount_rule = DiscountRule::findOrFail($id);
			$discount_rule->fill($request->input());


			$valid = $discount_rule->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$discount_rule->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/discount_rules/id/'.$id);
			} else {
					return view('discount_rules.edit', [
							'discount_rule'=>$discount_rule,
					'sponsor' => Sponsor::all()->sortBy('sponsor_name')->lists('sponsor_name', 'sponsor_code')->prepend('',''),
					'product' => Product::all()->sortBy('product_name')->lists('product_name', 'product_code')->prepend('',''),
					'category' => ProductCategory::all()->sortBy('category_name')->lists('category_name', 'category_code')->prepend('',''),
					'parent' => ProductCategoryParent::all()->sortBy('parent_name')->lists('parent_name', 'parent_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$discount_rule = DiscountRule::findOrFail($id);
		return view('discount_rules.destroy', [
			'discount_rule'=>$discount_rule
			]);

	}
	public function destroy($id)
	{	
			DiscountRule::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/discount_rules');
	}
	
	public function search(Request $request)
	{
			$discount_rules = DiscountRule::where('sponsor_code','like','%'.$request->search.'%')
					->orWhere('rule_id', 'like','%'.$request->search.'%')
					->orderBy('sponsor_code')
					->paginate($this->paginateValue);

			return view('discount_rules.index', [
					'discount_rules'=>$discount_rules,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$discount_rules = DiscountRule::where('rule_id','=',$id)
					->paginate($this->paginateValue);

			return view('discount_rules.index', [
					'discount_rules'=>$discount_rules
			]);
	}
}
