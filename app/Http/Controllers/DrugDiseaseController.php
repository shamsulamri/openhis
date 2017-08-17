<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DrugDisease;
use Log;
use DB;
use Session;
use App\Drug;
use App\Indication;


class DrugDiseaseController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$drug_diseases = DB::table('drug_diseases')
					->orderBy('drug_code')
					->paginate($this->paginateValue);
			return view('drug_diseases.index', [
					'drug_diseases'=>$drug_diseases
			]);
	}

	public function create()
	{
			$drug_disease = new DrugDisease();
			return view('drug_diseases.create', [
					'drug_disease' => $drug_disease,
					'drug' => Drug::all()->sortBy('drug_name')->lists('drug_name', 'drug_code')->prepend('',''),
					'indication' => Indication::all()->sortBy('indication_name')->lists('indication_name', 'indication_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$drug_disease = new DrugDisease();
			$valid = $drug_disease->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$drug_disease = new DrugDisease($request->all());
					$drug_disease->disease_id = $request->disease_id;
					$drug_disease->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/drug_diseases/id/'.$drug_disease->disease_id);
			} else {
					return redirect('/drug_diseases/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$drug_disease = DrugDisease::findOrFail($id);
			return view('drug_diseases.edit', [
					'drug_disease'=>$drug_disease,
					'drug' => Drug::all()->sortBy('drug_name')->lists('drug_name', 'drug_code')->prepend('',''),
					'indication' => Indication::all()->sortBy('indication_name')->lists('indication_name', 'indication_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$drug_disease = DrugDisease::findOrFail($id);
			$drug_disease->fill($request->input());


			$valid = $drug_disease->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$drug_disease->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/drug_diseases/id/'.$id);
			} else {
					return view('drug_diseases.edit', [
							'drug_disease'=>$drug_disease,
					'drug' => Drug::all()->sortBy('drug_name')->lists('drug_name', 'drug_code')->prepend('',''),
					'indication' => Indication::all()->sortBy('indication_name')->lists('indication_name', 'indication_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$drug_disease = DrugDisease::findOrFail($id);
		return view('drug_diseases.destroy', [
			'drug_disease'=>$drug_disease
			]);

	}
	public function destroy($id)
	{	
			DrugDisease::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/drug_diseases');
	}
	
	public function search(Request $request)
	{
			$drug_diseases = DB::table('drug_diseases')
					->where('drug_code','like','%'.$request->search.'%')
					->orWhere('disease_id', 'like','%'.$request->search.'%')
					->orderBy('drug_code')
					->paginate($this->paginateValue);

			return view('drug_diseases.index', [
					'drug_diseases'=>$drug_diseases,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$drug_diseases = DB::table('drug_diseases')
					->where('disease_id','=',$id)
					->paginate($this->paginateValue);

			return view('drug_diseases.index', [
					'drug_diseases'=>$drug_diseases
			]);
	}
}
