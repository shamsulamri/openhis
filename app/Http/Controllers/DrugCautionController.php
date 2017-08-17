<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DrugCaution;
use Log;
use DB;
use Session;


class DrugCautionController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$drug_cautions = DB::table('drug_cautions')
					->orderBy('caution_code')
					->paginate($this->paginateValue);
			return view('drug_cautions.index', [
					'drug_cautions'=>$drug_cautions
			]);
	}

	public function create()
	{
			$drug_caution = new DrugCaution();
			return view('drug_cautions.create', [
					'drug_caution' => $drug_caution,
				
					]);
	}

	public function store(Request $request) 
	{
			$drug_caution = new DrugCaution();
			$valid = $drug_caution->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$drug_caution = new DrugCaution($request->all());
					$drug_caution->caution_code = $request->caution_code;
					$drug_caution->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/drug_cautions/id/'.$drug_caution->caution_code);
			} else {
					return redirect('/drug_cautions/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$drug_caution = DrugCaution::findOrFail($id);
			return view('drug_cautions.edit', [
					'drug_caution'=>$drug_caution,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$drug_caution = DrugCaution::findOrFail($id);
			$drug_caution->fill($request->input());


			$valid = $drug_caution->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$drug_caution->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/drug_cautions/id/'.$id);
			} else {
					return view('drug_cautions.edit', [
							'drug_caution'=>$drug_caution,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$drug_caution = DrugCaution::findOrFail($id);
		return view('drug_cautions.destroy', [
			'drug_caution'=>$drug_caution
			]);

	}
	public function destroy($id)
	{	
			DrugCaution::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/drug_cautions');
	}
	
	public function search(Request $request)
	{
			$drug_cautions = DB::table('drug_cautions')
					->where('caution_code','like','%'.$request->search.'%')
					->orWhere('caution_code', 'like','%'.$request->search.'%')
					->orderBy('caution_code')
					->paginate($this->paginateValue);

			return view('drug_cautions.index', [
					'drug_cautions'=>$drug_cautions,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$drug_cautions = DB::table('drug_cautions')
					->where('caution_code','=',$id)
					->paginate($this->paginateValue);

			return view('drug_cautions.index', [
					'drug_cautions'=>$drug_cautions
			]);
	}
}
