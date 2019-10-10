<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\OrderImaging;
use Log;
use DB;
use Session;
use App\Consultation;
use App\Order;
use App\Product;
use App\OrderHelper;

class OrderImagingController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$order_imaging = OrderImaging::orderBy('side')
					->paginate($this->paginateValue);

			return view('order_imaging.index', [
					'order_imaging'=>$order_imaging
			]);
	}

	public function create()
	{
			$order_imaging = new OrderImaging();
			return view('order_imaging.create', [
					'order_imaging' => $order_imaging,
				
					]);
	}

	public function store(Request $request) 
	{
			$order_imaging = new OrderImaging();
			$valid = $order_imaging->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$order_imaging = new OrderImaging($request->all());
					$order_imaging->product_code = $request->product_code;
					$order_imaging->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/order_imaging/id/'.$order_imaging->product_code);
			} else {
					return redirect('/order_imaging/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$order_imaging = OrderImaging::findOrFail($id);
			return view('order_imaging.edit', [
					'order_imaging'=>$order_imaging,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$order_imaging = OrderImaging::findOrFail($id);
			$order_imaging->fill($request->input());


			$valid = $order_imaging->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$order_imaging->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/order_imaging/id/'.$id);
			} else {
					return view('order_imaging.edit', [
							'order_imaging'=>$order_imaging,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$order_imaging = OrderImaging::findOrFail($id);
		return view('order_imaging.destroy', [
			'order_imaging'=>$order_imaging
			]);

	}
	public function destroy($id)
	{	
			OrderImaging::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/order_imaging');
	}
	
	public function search(Request $request)
	{
			$order_imaging = OrderImaging::where('side','like','%'.$request->search.'%')
					->orWhere('product_code', 'like','%'.$request->search.'%')
					->orderBy('side')
					->paginate($this->paginateValue);

			return view('order_imaging.index', [
					'order_imaging'=>$order_imaging,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$order_imaging = OrderImaging::where('product_code','=',$id)
					->paginate($this->paginateValue);

			return view('order_imaging.index', [
					'order_imaging'=>$order_imaging
			]);
	}

	public function imaging(Request $request)
	{
			$consultation = Consultation::find(Session::get('consultation_id'));
			$encounter = $consultation->encounter;
			$params = DB::table('order_imaging')
						->get();

			/*
			$procedures = Product::where('category_code', 'radiography')
								->orderBy('product_name')
								->lists('product_name', 'product_code');
			 */
			$procedures = OrderImaging::orderBy('product_name')
							->leftjoin('products as b', 'b.product_code', '=', 'order_imaging.product_code')
							->lists('product_name', 'b.product_code');
			
			$orders = Order::where('consultation_id', $consultation->consultation_id)
						->leftJoin('products as a', 'a.product_code', '=', 'orders.product_code')
						->where('category_code', 'imaging2')
						->where('post_id', 0)
						->orderBy('order_id', 'desc')
						->get();

			$sides = [''];
			$regions = [''];
			$views = [''];

			return view('order_imaging.imaging', [
					'consultation'=>$consultation,
					'sides'=>$sides,
					'regions'=>$regions,
					'views'=>$views,
					'procedures'=>$procedures,
					'params'=>$params,
					'orders'=>$orders,
					'product_code'=>$request->product_code?:null,
					'view_code'=>$request->view_code?:null,
					'side_code'=>$request->side_code?:null,
					'region_code'=>$request->region_code?:null,
					'patient'=>$encounter->patient,
					'plan'=>'imaging',
			]);
	}

	public function deleteImaging($id)
	{	
			Order::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/imaging');
	}

	public function addImaging(Request $request)
	{

			$product = Product::find($request->product_code);
			if ($product) {
					$order_id = OrderHelper::orderItem($product, $request->cookie('ward'));
					$order = Order::find($order_id);
					$description = "";
					if ($request->side) $description .= $request->side." > ";
					if ($request->region) $description .= $request->region." > ";
					if ($request->views) $description .= $request->views;

					$description = rtrim($description, " > ");
					$order->order_description = $description;
					$order->save();
			}

			$url = sprintf('product_code=%s&plan=imaging',
				$request->product_code
			);

			Session::flash('message', 'Record successfully created.');

			return redirect('/imaging?'.$url);
	}

}
