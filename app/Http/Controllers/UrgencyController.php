<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Urgency;
use Log;
use DB;
use Session;


class UrgencyController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$urgencies = DB::table('ref_urgencies')
					->orderBy('urgency_name')
					->paginate($this->paginateValue);
			return view('urgencies.index', [
					'urgencies'=>$urgencies
			]);
	}

	public function create()
	{
			$urgency = new Urgency();
			return view('urgencies.create', [
					'urgency' => $urgency,
				
					]);
	}

	public function store(Request $request) 
	{
			$urgency = new Urgency();
			$valid = $urgency->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$urgency = new Urgency($request->all());
					$urgency->urgency_code = $request->urgency_code;
					$urgency->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/urgencies/id/'.$urgency->urgency_code);
			} else {
					return redirect('/urgencies/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$urgency = Urgency::findOrFail($id);
			return view('urgencies.edit', [
					'urgency'=>$urgency,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$urgency = Urgency::findOrFail($id);
			$urgency->fill($request->input());


			$valid = $urgency->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$urgency->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/urgencies/id/'.$id);
			} else {
					return view('urgencies.edit', [
							'urgency'=>$urgency,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$urgency = Urgency::findOrFail($id);
		return view('urgencies.destroy', [
			'urgency'=>$urgency
			]);

	}
	public function destroy($id)
	{	
			Urgency::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/urgencies');
	}
	
	public function search(Request $request)
	{
			$urgencies = DB::table('ref_urgencies')
					->where('urgency_name','like','%'.$request->search.'%')
					->orWhere('urgency_code', 'like','%'.$request->search.'%')
					->orderBy('urgency_name')
					->paginate($this->paginateValue);

			return view('urgencies.index', [
					'urgencies'=>$urgencies,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$urgencies = DB::table('ref_urgencies')
					->where('urgency_code','=',$id)
					->paginate($this->paginateValue);

			return view('urgencies.index', [
					'urgencies'=>$urgencies
			]);
	}
}
