<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\PatientDependant;
use Log;
use DB;
use Session;
use App\Relationship as Relation;


class PatientDependantController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$patient_dependants = DB::table('patient_dependants')
					->orderBy('patient_id')
					->paginate($this->paginateValue);
			return view('patient_dependants.index', [
					'patient_dependants'=>$patient_dependants
			]);
	}

	public function create()
	{
			$patient_dependant = new PatientDependant();
			return view('patient_dependants.create', [
					'patient_dependant' => $patient_dependant,
					'relation' => Relation::all()->sortBy('relation_name')->lists('relation_name', 'relation_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$patient_dependant = new PatientDependant();
			$valid = $patient_dependant->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$patient_dependant = new PatientDependant($request->all());
					$patient_dependant->id = $request->id;
					$patient_dependant->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/patient_dependants/id/'.$patient_dependant->id);
			} else {
					return redirect('/patient_dependants/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$patient_dependant = PatientDependant::findOrFail($id);
			return view('patient_dependants.edit', [
					'patient_dependant'=>$patient_dependant,
					'relation' => Relation::all()->sortBy('relation_name')->lists('relation_name', 'relation_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$patient_dependant = PatientDependant::findOrFail($id);
			$patient_dependant->fill($request->input());


			$valid = $patient_dependant->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$patient_dependant->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/patient_dependants/id/'.$id);
			} else {
					return view('patient_dependants.edit', [
							'patient_dependant'=>$patient_dependant,
					'relation' => Relation::all()->sortBy('relation_name')->lists('relation_name', 'relation_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$patient_dependant = PatientDependant::findOrFail($id);
		return view('patient_dependants.destroy', [
			'patient_dependant'=>$patient_dependant
			]);

	}
	public function destroy($id)
	{	
			$patient_dependant = PatientDependant::findOrFail($id);
			PatientDependant::where('patient_id','=',$patient_dependant->dependant_id)
					->where('dependant_id','=', $patient_dependant->patient_id)
					->delete();
			PatientDependant::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/dependants?patient_id='.$patient_dependant->patient_id);
	}
	
	public function search(Request $request)
	{
			$patient_dependants = DB::table('patient_dependants')
					->where('patient_id','like','%'.$request->search.'%')
					->orWhere('id', 'like','%'.$request->search.'%')
					->orderBy('patient_id')
					->paginate($this->paginateValue);

			return view('patient_dependants.index', [
					'patient_dependants'=>$patient_dependants,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$patient_dependants = DB::table('patient_dependants')
					->where('id','=',$id)
					->paginate($this->paginateValue);

			return view('patient_dependants.index', [
					'patient_dependants'=>$patient_dependants
			]);
	}
}
