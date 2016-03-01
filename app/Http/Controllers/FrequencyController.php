<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Frequency;
use Log;
use DB;
use Session;


class FrequencyController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$frequencies = DB::table('ref_frequencies')
					->orderBy('frequency_name')
					->paginate($this->paginateValue);
			return view('frequencies.index', [
					'frequencies'=>$frequencies
			]);
	}

	public function create()
	{
			$frequency = new Frequency();
			return view('frequencies.create', [
					'frequency' => $frequency,
				
					]);
	}

	public function store(Request $request) 
	{
			$frequency = new Frequency();
			$valid = $frequency->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$frequency = new Frequency($request->all());
					$frequency->frequency_code = $request->frequency_code;
					$frequency->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/frequencies/id/'.$frequency->frequency_code);
			} else {
					return redirect('/frequencies/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$frequency = Frequency::findOrFail($id);
			return view('frequencies.edit', [
					'frequency'=>$frequency,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$frequency = Frequency::findOrFail($id);
			$frequency->fill($request->input());


			$valid = $frequency->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$frequency->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/frequencies/id/'.$id);
			} else {
					return view('frequencies.edit', [
							'frequency'=>$frequency,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$frequency = Frequency::findOrFail($id);
		return view('frequencies.destroy', [
			'frequency'=>$frequency
			]);

	}
	public function destroy($id)
	{	
			Frequency::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/frequencies');
	}
	
	public function search(Request $request)
	{
			$frequencies = DB::table('ref_frequencies')
					->where('frequency_name','like','%'.$request->search.'%')
					->orWhere('frequency_code', 'like','%'.$request->search.'%')
					->orderBy('frequency_name')
					->paginate($this->paginateValue);

			return view('frequencies.index', [
					'frequencies'=>$frequencies,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$frequencies = DB::table('ref_frequencies')
					->where('frequency_code','=',$id)
					->paginate($this->paginateValue);

			return view('frequencies.index', [
					'frequencies'=>$frequencies
			]);
	}
}
