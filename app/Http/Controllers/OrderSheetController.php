<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Encounter;
use Log;
use DB;
use Session;
use Gate;
use Auth;
use App\DojoUtility;
use App\Order;
use App\OrderCancellation;
use App\ProductCategory;
use App\OrderHelper;

class OrderSheetController extends Controller {
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index($id)
	{
			$encounter = Encounter::findOrFail($id);

			$authorized_categories = Auth::user()->categoryList();
			//return $authorized_categories;
			//$orders = $encounter->orders()->get();

			/*
			$orders = Order::selectRaw('orders.order_id, orders.product_code, product_name, category_name, orders.created_at as order_at, order_completed, 
					d.name as orderer_name, order_quantity_supply, order_discount, b.category_code,
					cancel_id, cancel_reason, f.name as cancel_name, e.created_at as cancel_at,
					g.name as update_name,  orders.updated_at as update_at
					')
						->leftjoin('products as b', 'b.product_code', '=', 'orders.product_code')
						->leftjoin('product_categories as c', 'c.category_code', '=', 'b.category_code')
						->leftjoin('users as d', 'd.id', '=', 'orders.user_id')
						->leftjoin('order_cancellations as e', 'e.order_id', '=', 'orders.order_id')
						->leftjoin('users as f', 'f.id', '=', 'e.user_id')
						->leftjoin('users as g', 'g.id', '=', 'orders.updated_by')
						->orderBy('category_name')
						->orderBy('product_name')
						->orderBy('orders.created_at')
						->where('encounter_id', $id);
			 */

			$base = Order::where('encounter_id', $id)
						->leftjoin('products as b', 'b.product_code', '=', 'orders.product_code')
						->leftjoin('product_categories as c', 'c.category_code', '=', 'b.category_code')
						->leftjoin('users as d', 'd.id', '=', 'orders.user_id')
						->leftjoin('order_cancellations as e', 'e.order_id', '=', 'orders.order_id')
						->leftjoin('users as f', 'f.id', '=', 'e.user_id')
						->leftjoin('users as g', 'g.id', '=', 'orders.updated_by')
						->where('b.category_code', '<>', 'bed')
						->orderBy('category_name')
						->orderBy('product_name')
						->orderBy('orders.created_at');
						
			$categories = $base->distinct()
							->get(['b.category_code']);


			$orders = $base->selectRaw('orders.order_id, orders.product_code, product_name, category_name, orders.created_at as order_at, order_completed, 
					d.name as orderer_name, order_quantity_supply, order_discount, b.category_code,
					cancel_id, cancel_reason, f.name as cancel_name, e.created_at as cancel_at,
					g.name as update_name,  orders.updated_at as update_at, orders.post_id, order_unit_price, orders.encounter_id, bom_code
					')->get();

			$keys = [];
			foreach($categories as $category) {
				$category_code = $category->category_code;
				if (array_key_exists($category_code, $authorized_categories->toArray())) {
					array_push($keys, $category_code);
				}
			}

			$bookmarks = ProductCategory::select('category_code', 'category_name')->whereIn('category_code', $keys)->orderBy('category_name')->get();

			return view('order_sheets.index', [
					'encounter'=>$encounter,
					'patient'=>$encounter->patient,
					'orders'=>$orders,
					'authorized_categories'=>$authorized_categories->toArray(),
					'bookmarks'=>$bookmarks,
					'helper'=>new OrderHelper(),
			]);
	}

	public function update(Request $request, $id)
	{
			$encounter = Encounter::findOrFail($id);

			$orders = $encounter->orders()->get();

			foreach ($orders as $order) {
				$is_dirty = false;
				$supply = $request[$order->order_id."_supply"]?:$order->order_quantity_supply;
				$discount = $request[$order->order_id."_discount"]?:$order->order_discount;
				$unit_price = $request[$order->order_id."_unit_price"]?:$order->order_unit_price;
				$completed = $request[$order->order_id."_completed"]?:0;

				Log::info($order->order_completed." - ". $completed);
				if ($order->order_completed != $completed) {
					$order->order_completed = $completed;
					$is_dirty = true;
				}

				if ($order->order_quantity_supply != $supply) {
					$order->order_quantity_supply = $supply?:1;
					$order->order_completed = 1;
					$is_dirty = true;
				}

				if ($order->order_discount != $discount) {
					$order->order_discount = $discount;
					$order->order_completed = 1;
					$is_dirty = true;
				}

				if ($order->order_unit_price != $unit_price) {
					$order->order_unit_price = $unit_price;
					$order->order_completed = 1;
					$is_dirty = true;
				}

				if ($is_dirty) {
					$order->updated_by = Auth::user()->id;
					$order->save();
					Log::info($unit_price);
				}
			}

			Session::flash('message', 'Record successfully updated.');
			if (Auth::user()->author_id==19 or Auth::user()->author_id==6) {
					return redirect('/bill_items/generate/'.$encounter->encounter_id);
					//return redirect('/order_sheet/'.$encounter->encounter_id);
			} else {
					if ($encounter->encounter_code == 'inpatient') {
							return redirect('/admissions/'.$encounter->admission->admission_id);
					} else {
							return redirect('/queues');
					}
			}
	}

	public function cancel($id)
	{
			$order = Order::findOrFail($id);
			$encounter = $order->encounter;

			return view('order_sheets.cancel', [
					'patient' => $encounter->patient,
					'order_cancellation'=>new OrderCancellation(),
					'order'=>$order,
					'cancel_id'=>null,
					]);
	}

	public function cancel_post(Request $request, $encounter_id)
	{
			$order_id = $request->order_id;
			$order_cancellation = new OrderCancellation();
			$valid = $order_cancellation->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$order_cancellation = new OrderCancellation($request->all());
					if ($request->cancel_id) {
						$order_cancellation = OrderCancellation::findOrFail($request->cancel_id);
						$order_cancellation->cancel_reason = $request->cancel_reason;
					}
					$order_cancellation->order_id = $request->order_id;
					$order_cancellation->user_id = Auth::user()->id;
					$order_cancellation->save();

					Session::flash('message', 'Record successfully created.');
					return redirect('/order_sheet/'.$encounter_id);
			} else {
					return redirect('/order_sheet/cancel/'.$order_id)
							->withErrors($valid)
							->withInput();
			}
	}

	public function cancel_edit($id)
	{
			$order_cancellation = OrderCancellation::findOrFail($id);
			$order = Order::findOrFail($order_cancellation->order_id);
			$encounter = $order->encounter;

			return view('order_sheets.cancel', [
					'patient' => $encounter->patient,
					'order_cancellation'=>$order_cancellation,
					'order'=>$order,
					'cancel_id'=>$id,
					]);
	}

	public function cancel_delete($id)
	{
			$order_cancellation = OrderCancellation::findOrFail($id);
			return view('order_sheets.destroy', [
					'order_cancellation'=>$order_cancellation,
					'patient'=>$order_cancellation->order->encounter->patient,
			]);
	}

	public function cancel_destroy($id)
	{	
			$order_cancellation = OrderCancellation::findOrFail($id);
			$encounter_id = $order_cancellation->order->encounter_id;
			OrderCancellation::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/order_sheet/'.$encounter_id);
	}
}
