<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Store;
use Log;
use DB;
use Session;
use App\Ward;


class StoreController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$stores = DB::table('stores')
					->orderBy('store_name')
					->paginate($this->paginateValue);
			return view('stores.index', [
					'stores'=>$stores
			]);
	}

	public function create()
	{
			$store = new Store();
			return view('stores.create', [
					'store' => $store,
				
					]);
	}

	public function store(Request $request) 
	{
			$store = new Store();
			$valid = $store->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$store = new Store($request->all());
					$store->store_code = $request->store_code;
					$store->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/stores/id/'.$store->store_code);
			} else {
					return redirect('/stores/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$store = Store::findOrFail($id);
			return view('stores.edit', [
					'store'=>$store,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$store = Store::findOrFail($id);
			$store->fill($request->input());

			$store->store_receiving = $request->store_receiving ?: 0;

			$valid = $store->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$store->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/stores/id/'.$id);
			} else {
					return view('stores.edit', [
							'store'=>$store,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$store = Store::findOrFail($id);
		return view('stores.destroy', [
			'store'=>$store
			]);

	}
	public function destroy($id)
	{	
			Store::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/stores');
	}
	
	public function search(Request $request)
	{
			$stores = DB::table('stores')
					->where('store_name','like','%'.$request->search.'%')
					->orWhere('store_code', 'like','%'.$request->search.'%')
					->orderBy('store_name')
					->paginate($this->paginateValue);

			return view('stores.index', [
					'stores'=>$stores,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$stores = DB::table('stores')
					->where('store_code','=',$id)
					->paginate($this->paginateValue);

			return view('stores.index', [
					'stores'=>$stores
			]);
	}

	public function generate()
	{
			$wards = Ward::all();

			foreach($wards as $ward) {
				$store = new Store();
				$store->store_code = $ward->ward_code;
				$store->store_name = $ward->ward_name." STORE";
				$store->save();

				$ward->store_code = $store->store_code;
				$ward->save();
			}
			return "Ok";
	}

	public function setStore($id) 
	{
			$store = Store::find($id);
			Session::flash('message', 'The store has been set to '.$store->store_name);

			return redirect('/stores')
					->withCookie(cookie('store',$id, 2628000));
					//->withCookie(\Cookie::forget('queue_location'));
	}

	public function forgetCookie() {
			Session::flash('message', 'Store detached.');
			return redirect('/stores')
				->withCookie(\Cookie::forget('store'));
	}
}
