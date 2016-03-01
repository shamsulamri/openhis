<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DrugDosage;
use Log;
use DB;
use Session;


class DrugDosageController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$drug_dosages = DB::table('drug_dosages')
					->orderBy('dosage_name')
					->paginate($this->paginateValue);
			return view('drug_dosages.index', [
					'drug_dosages'=>$drug_dosages
			]);
	}

	public function create()
	{
			$drug_dosage = new DrugDosage();
			return view('drug_dosages.create', [
					'drug_dosage' => $drug_dosage,
				
					]);
	}

	public function store(Request $request) 
	{
			$drug_dosage = new DrugDosage();
			$valid = $drug_dosage->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$drug_dosage = new DrugDosage($request->all());
					$drug_dosage->dosage_code = $request->dosage_code;
					$drug_dosage->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/drug_dosages/id/'.$drug_dosage->dosage_code);
			} else {
					return redirect('/drug_dosages/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$drug_dosage = DrugDosage::findOrFail($id);
			return view('drug_dosages.edit', [
					'drug_dosage'=>$drug_dosage,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$drug_dosage = DrugDosage::findOrFail($id);
			$drug_dosage->fill($request->input());


			$valid = $drug_dosage->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$drug_dosage->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/drug_dosages/id/'.$id);
			} else {
					return view('drug_dosages.edit', [
							'drug_dosage'=>$drug_dosage,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$drug_dosage = DrugDosage::findOrFail($id);
		return view('drug_dosages.destroy', [
			'drug_dosage'=>$drug_dosage
			]);

	}
	public function destroy($id)
	{	
			DrugDosage::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/drug_dosages');
	}
	
	public function search(Request $request)
	{
			$drug_dosages = DB::table('drug_dosages')
					->where('dosage_name','like','%'.$request->search.'%')
					->orWhere('dosage_code', 'like','%'.$request->search.'%')
					->orderBy('dosage_name')
					->paginate($this->paginateValue);

			return view('drug_dosages.index', [
					'drug_dosages'=>$drug_dosages,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$drug_dosages = DB::table('drug_dosages')
					->where('dosage_code','=',$id)
					->paginate($this->paginateValue);

			return view('drug_dosages.index', [
					'drug_dosages'=>$drug_dosages
			]);
	}
}
