<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Gemder;
use Log;
use DB;
use Session;


class GemderController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$genders = DB::table('ref_genders')
					->orderBy('gender_name')
					->paginate($this->paginateValue);
			return view('genders.index', [
					'genders'=>$genders
			]);
	}

	public function create()
	{
			$gemder = new Gemder();
			return view('genders.create', [
					'gemder' => $gemder,
				
					]);
	}

	public function store(Request $request) 
	{
			$gemder = new Gemder();
			$valid = $gemder->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$gemder = new Gemder($request->all());
					$gemder->gender_code = $request->gender_code;
					$gemder->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/genders/id/'.$gemder->gender_code);
			} else {
					return redirect('/genders/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$gemder = Gemder::findOrFail($id);
			return view('genders.edit', [
					'gemder'=>$gemder,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$gemder = Gemder::findOrFail($id);
			$gemder->fill($request->input());


			$valid = $gemder->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$gemder->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/genders/id/'.$id);
			} else {
					return view('genders.edit', [
							'gemder'=>$gemder,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$gemder = Gemder::findOrFail($id);
		return view('genders.destroy', [
			'gemder'=>$gemder
			]);

	}
	public function destroy($id)
	{	
			Gemder::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/genders');
	}
	
	public function search(Request $request)
	{
			$genders = DB::table('ref_genders')
					->where('gender_name','like','%'.$request->search.'%')
					->orWhere('gender_code', 'like','%'.$request->search.'%')
					->orderBy('gender_name')
					->paginate($this->paginateValue);

			return view('genders.index', [
					'genders'=>$genders,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$genders = DB::table('ref_genders')
					->where('gender_code','=',$id)
					->paginate($this->paginateValue);

			return view('genders.index', [
					'genders'=>$genders
			]);
	}
}
