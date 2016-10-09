<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Sponsor;
use Log;
use DB;
use Session;
use App\State;
use App\Nation;

class SponsorController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$sponsors = DB::table('sponsors')
					->orderBy('sponsor_name')
					->paginate($this->paginateValue);
			return view('sponsors.index', [
					'sponsors'=>$sponsors
			]);
	}

	public function create()
	{
			$sponsor = new Sponsor();
			return view('sponsors.create', [
					'sponsor' => $sponsor,
					'state' => State::all()->sortBy('state_name')->lists('state_name', 'state_code')->prepend('',''),
					'nation' => Nation::all()->sortBy('nation_name')->lists('nation_name', 'nation_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$sponsor = new Sponsor();
			$valid = $sponsor->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$sponsor = new Sponsor($request->all());
					$sponsor->sponsor_code = $request->sponsor_code;
					$sponsor->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/sponsors/id/'.$sponsor->sponsor_code);
			} else {
					return redirect('/sponsors/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$sponsor = Sponsor::findOrFail($id);
			return view('sponsors.edit', [
					'sponsor'=>$sponsor,
					'state' => State::all()->sortBy('state_name')->lists('state_name', 'state_code')->prepend('',''),
					'nation' => Nation::all()->sortBy('nation_name')->lists('nation_name', 'nation_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$sponsor = Sponsor::findOrFail($id);
			$sponsor->fill($request->input());


			$valid = $sponsor->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$sponsor->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/sponsors/id/'.$id);
			} else {
					return view('sponsors.edit', [
							'sponsor'=>$sponsor,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$sponsor = Sponsor::findOrFail($id);
		return view('sponsors.destroy', [
			'sponsor'=>$sponsor
			]);

	}
	public function destroy($id)
	{	
			Sponsor::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/sponsors');
	}
	
	public function search(Request $request)
	{
			$sponsors = DB::table('sponsors')
					->where('sponsor_name','like','%'.$request->search.'%')
					->orWhere('sponsor_code', 'like','%'.$request->search.'%')
					->orderBy('sponsor_name')
					->paginate($this->paginateValue);

			return view('sponsors.index', [
					'sponsors'=>$sponsors,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$sponsors = DB::table('sponsors')
					->where('sponsor_code','=',$id)
					->paginate($this->paginateValue);

			return view('sponsors.index', [
					'sponsors'=>$sponsors
			]);
	}
}
