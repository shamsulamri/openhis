<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Postcode;
use Log;
use DB;
use Session;


class PostcodeController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$postcodes = DB::table('postcodes')
					->orderBy('postcode')
					->paginate($this->paginateValue);
			return view('postcodes.index', [
					'postcodes'=>$postcodes
			]);
	}

	public function create()
	{
			$postcode = new Postcode();
			return view('postcodes.create', [
					'postcode' => $postcode,
				
					]);
	}

	public function store(Request $request) 
	{
			$postcode = new Postcode();
			$valid = $postcode->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$postcode = new Postcode($request->all());
					$postcode->postcode = $request->postcode;
					$postcode->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/postcodes/id/'.$postcode->postcode);
			} else {
					return redirect('/postcodes/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$postcode = Postcode::findOrFail($id);
			return view('postcodes.edit', [
					'postcode'=>$postcode,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$postcode = Postcode::findOrFail($id);
			$postcode->fill($request->input());


			$valid = $postcode->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$postcode->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/postcodes/id/'.$id);
			} else {
					return view('postcodes.edit', [
							'postcode'=>$postcode,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$postcode = Postcode::findOrFail($id);
		return view('postcodes.destroy', [
			'postcode'=>$postcode
			]);

	}
	public function destroy($id)
	{	
			Postcode::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/postcodes');
	}
	
	public function search(Request $request)
	{
			$postcodes = DB::table('postcodes')
					->where('postcode','like','%'.$request->search.'%')
					->orWhere('postcode', 'like','%'.$request->search.'%')
					->orderBy('postcode')
					->paginate($this->paginateValue);

			return view('postcodes.index', [
					'postcodes'=>$postcodes,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$postcodes = DB::table('postcodes')
					->where('postcode','=',$id)
					->paginate($this->paginateValue);

			return view('postcodes.index', [
					'postcodes'=>$postcodes
			]);
	}
}
