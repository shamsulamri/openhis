<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\StoreAuthorization;
use Log;
use DB;
use Session;
use App\Store;
use App\UserAuthorization;

class StoreAuthorizationController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$store_authorizations = StoreAuthorization::orderBy('author_id');

			$store_authorizations = $store_authorizations->paginate($this->paginateValue);

			return view('store_authorizations.index', [
					'store_authorizations'=>$store_authorizations
			]);
	}

	public function create()
	{
			$store_authorization = new StoreAuthorization();
			return view('store_authorizations.create', [
					'store_authorization' => $store_authorization,
					'store' => Store::all()->sortBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
					'authorizations' => UserAuthorization::all()->sortBy('author_name')->lists('author_name', 'author_id')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$store_authorization = new StoreAuthorization();
			$valid = $store_authorization->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$store_authorization = new StoreAuthorization($request->all());
					$store_authorization->id = $request->id;
					$store_authorization->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/store_authorizations/id/'.$store_authorization->id);
			} else {
					return redirect('/store_authorizations/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$store_authorization = StoreAuthorization::findOrFail($id);
			return view('store_authorizations.edit', [
					'store_authorization'=>$store_authorization,
					'store' => Store::all()->sortBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
					'authorizations' => UserAuthorization::all()->sortBy('author_name')->lists('author_name', 'author_id')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$store_authorization = StoreAuthorization::findOrFail($id);
			$store_authorization->fill($request->input());


			$valid = $store_authorization->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$store_authorization->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/store_authorizations/id/'.$id);
			} else {
					return view('store_authorizations.edit', [
							'store_authorization'=>$store_authorization,
					'store' => Store::all()->sortBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$store_authorization = StoreAuthorization::findOrFail($id);
		return view('store_authorizations.destroy', [
			'store_authorization'=>$store_authorization
			]);

	}
	public function destroy($id)
	{	
			StoreAuthorization::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/store_authorizations');
	}
	
	public function search(Request $request)
	{
			$store_authorizations = StoreAuthorization::orderBy('author_name')
					->leftjoin('user_authorizations as a', 'a.author_id', '=', 'store_authorizations.author_id')
					->leftjoin('stores as b', 'b.store_code', '=', 'store_authorizations.store_code')
					->where('author_name','like','%'.$request->search.'%')
					->orWhere('store_name','like','%'.$request->search.'%')
					->paginate($this->paginateValue);

			return view('store_authorizations.index', [
					'store_authorizations'=>$store_authorizations,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$store_authorizations = StoreAuthorization::where('id','=',$id)
					->paginate($this->paginateValue);

			return view('store_authorizations.index', [
					'store_authorizations'=>$store_authorizations
			]);
	}
}
