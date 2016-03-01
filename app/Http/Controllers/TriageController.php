<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Triage;
use Log;
use DB;
use Session;


class TriageController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$triages = DB::table('triages')
					->orderBy('triage_name')
					->paginate($this->paginateValue);
			return view('triages.index', [
					'triages'=>$triages
			]);
	}

	public function create()
	{
			$triage = new Triage();
			return view('triages.create', [
					'triage' => $triage,
				
					]);
	}

	public function store(Request $request) 
	{
			$triage = new Triage();
			$valid = $triage->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$triage = new Triage($request->all());
					$triage->triage_code = $request->triage_code;
					$triage->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/triages/id/'.$triage->triage_code);
			} else {
					return redirect('/triages/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$triage = Triage::findOrFail($id);
			return view('triages.edit', [
					'triage'=>$triage,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$triage = Triage::findOrFail($id);
			$triage->fill($request->input());


			$valid = $triage->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$triage->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/triages/id/'.$id);
			} else {
					return view('triages.edit', [
							'triage'=>$triage,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$triage = Triage::findOrFail($id);
		return view('triages.destroy', [
			'triage'=>$triage
			]);

	}
	public function destroy($id)
	{	
			Triage::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/triages');
	}
	
	public function search(Request $request)
	{
			$triages = DB::table('triages')
					->where('triage_name','like','%'.$request->search.'%')
					->orWhere('triage_code', 'like','%'.$request->search.'%')
					->orderBy('triage_name')
					->paginate($this->paginateValue);

			return view('triages.index', [
					'triages'=>$triages,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$triages = DB::table('triages')
					->where('triage_code','=',$id)
					->paginate($this->paginateValue);

			return view('triages.index', [
					'triages'=>$triages
			]);
	}
}
