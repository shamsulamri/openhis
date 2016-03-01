<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DrugFrequency;
use Log;
use DB;
use Session;


class DrugFrequencyController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$drug_frequencies = DB::table('drug_frequencies')
					->orderBy('frequency_name')
					->paginate($this->paginateValue);
			return view('drug_frequencies.index', [
					'drug_frequencies'=>$drug_frequencies
			]);
	}

	public function create()
	{
			$drug_frequency = new DrugFrequency();
			return view('drug_frequencies.create', [
					'drug_frequency' => $drug_frequency,
				
					]);
	}

	public function store(Request $request) 
	{
			$drug_frequency = new DrugFrequency();
			$valid = $drug_frequency->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$drug_frequency = new DrugFrequency($request->all());
					$drug_frequency->frequency_code = $request->frequency_code;
					$drug_frequency->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/drug_frequencies/id/'.$drug_frequency->frequency_code);
			} else {
					return redirect('/drug_frequencies/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$drug_frequency = DrugFrequency::findOrFail($id);
			return view('drug_frequencies.edit', [
					'drug_frequency'=>$drug_frequency,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$drug_frequency = DrugFrequency::findOrFail($id);
			$drug_frequency->fill($request->input());


			$valid = $drug_frequency->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$drug_frequency->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/drug_frequencies/id/'.$id);
			} else {
					return view('drug_frequencies.edit', [
							'drug_frequency'=>$drug_frequency,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$drug_frequency = DrugFrequency::findOrFail($id);
		return view('drug_frequencies.destroy', [
			'drug_frequency'=>$drug_frequency
			]);

	}
	public function destroy($id)
	{	
			DrugFrequency::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/drug_frequencies');
	}
	
	public function search(Request $request)
	{
			$drug_frequencies = DB::table('drug_frequencies')
					->where('frequency_name','like','%'.$request->search.'%')
					->orWhere('frequency_code', 'like','%'.$request->search.'%')
					->orderBy('frequency_name')
					->paginate($this->paginateValue);

			return view('drug_frequencies.index', [
					'drug_frequencies'=>$drug_frequencies,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$drug_frequencies = DB::table('drug_frequencies')
					->where('frequency_code','=',$id)
					->paginate($this->paginateValue);

			return view('drug_frequencies.index', [
					'drug_frequencies'=>$drug_frequencies
			]);
	}
}
