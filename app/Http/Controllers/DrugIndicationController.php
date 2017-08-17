<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DrugIndication;
use Log;
use DB;
use Session;


class DrugIndicationController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$drug_indications = DB::table('drug_indications')
					->orderBy('indication_description')
					->paginate($this->paginateValue);
			return view('drug_indications.index', [
					'drug_indications'=>$drug_indications
			]);
	}

	public function create()
	{
			$drug_indication = new DrugIndication();
			return view('drug_indications.create', [
					'drug_indication' => $drug_indication,
				
					]);
	}

	public function store(Request $request) 
	{
			$drug_indication = new DrugIndication();
			$valid = $drug_indication->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$drug_indication = new DrugIndication($request->all());
					$drug_indication->indication_code = $request->indication_code;
					$drug_indication->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/drug_indications/id/'.$drug_indication->indication_code);
			} else {
					return redirect('/drug_indications/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$drug_indication = DrugIndication::findOrFail($id);
			return view('drug_indications.edit', [
					'drug_indication'=>$drug_indication,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$drug_indication = DrugIndication::findOrFail($id);
			$drug_indication->fill($request->input());


			$valid = $drug_indication->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$drug_indication->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/drug_indications/id/'.$id);
			} else {
					return view('drug_indications.edit', [
							'drug_indication'=>$drug_indication,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$drug_indication = DrugIndication::findOrFail($id);
		return view('drug_indications.destroy', [
			'drug_indication'=>$drug_indication
			]);

	}
	public function destroy($id)
	{	
			DrugIndication::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/drug_indications');
	}
	
	public function search(Request $request)
	{
			$drug_indications = DB::table('drug_indications')
					->where('indication_description','like','%'.$request->search.'%')
					->orWhere('indication_code', 'like','%'.$request->search.'%')
					->orderBy('indication_description')
					->paginate($this->paginateValue);

			return view('drug_indications.index', [
					'drug_indications'=>$drug_indications,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$drug_indications = DB::table('drug_indications')
					->where('indication_code','=',$id)
					->paginate($this->paginateValue);

			return view('drug_indications.index', [
					'drug_indications'=>$drug_indications
			]);
	}
}
