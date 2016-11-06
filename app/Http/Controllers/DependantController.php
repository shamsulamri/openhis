<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Dependant;
use Log;
use DB;
use Session;
use App\Gender;
use App\Religion;
use App\Title;
use App\MaritalStatus as Marital;
use App\Nation;
use App\Race;
use App\Occupation;
use App\Registration;
use App\PatientFlag as Flag;
use App\Relationship;
use App\Patient;
use App\PatientDependant;

class DependantController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index(Request $request)
	{
			$dependants = DB::table('patient_dependants as a')
					->leftJoin('patients as b', 'b.patient_id','=','a.dependant_id')
					->leftJoin('ref_relationships as c','c.relation_code','=','a.relation_code')
					->where('a.patient_id','=', $request->patient_id)
					->paginate($this->paginateValue);
		

			return view('dependants.index', [
					'dependants'=>$dependants,
					'patient_id'=>$request->patient_id,
			]);
	}

	public function create($patient_id)
	{
			$patient = Patient::find($patient_id);
			$dependant = new Dependant();
			$dependant->patient_related_mrn = $patient->patient_mrn;
			return view('dependants.create', [
					'dependant' => $dependant,
					'gender' => Gender::all()->sortBy('gender_name')->lists('gender_name', 'gender_code')->prepend('',''),
					'relation' => Relationship::all()->sortBy('relation_name')->lists('relation_name', 'relation_code')->prepend('',''),
					'relation_code'=>'',
					'patient_id'=>$patient_id,
					]);
	}

	public function store(Request $request) 
	{
			$dependant = new Dependant();
			$valid = $dependant->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$dependant = new Dependant($request->all());
					$dependant->save();
					$patient_dependant = new PatientDependant();
					$patient_dependant->patient_id = $request->patient_id;
					$patient_dependant->dependant_id = $dependant->patient_id;
					$patient_dependant->relation_code = $request->relation_code;
					$patient_dependant->save();

					$patient_dependant = new PatientDependant();
					$patient_dependant->dependant_id = $request->patient_id;
					$patient_dependant->patient_id = $dependant->patient_id;
					$patient_dependant->save();
					
					Session::flash('message', 'Record successfully created.');
					return redirect('/dependants?patient_id='.$request->patient_id);
			} else {
					return redirect('/dependants/create/'.$request->patient_id)
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit(Request $request, $id) 
	{
			$dependant = Dependant::findOrFail($id);
			$patient_dependant = PatientDependant::where('dependant_id','=', $id)
					->where('patient_id','=', $request->patient_id)
					->get()[0];
			return view('dependants.edit', [
					'dependant'=>$dependant,
					'gender' => Gender::all()->sortBy('gender_name')->lists('gender_name', 'gender_code')->prepend('',''),
					'relation' => Relationship::all()->sortBy('relation_name')->lists('relation_name', 'relation_code')->prepend('',''),
					'patient_id' => $request->patient_id,
					'relation_code' => $patient_dependant->relation_code,
					]);
	}

	public function update(Request $request, $id) 
	{
			$patient_dependant = PatientDependant::where('dependant_id','=', $id)
					->where('patient_id','=', $request->patient_id)
					->get()[0]; 
			$patient_dependant->relation_code = $request->relation_code;
			$patient_dependant->save();

			$dependant = Dependant::findOrFail($id);
			$dependant->fill($request->input());

			$valid = $dependant->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$dependant->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/dependants?patient_id='.$request->patient_id);
			} else {
					return view('dependants.edit', [
							'dependant'=>$dependant,
							'gender' => Gender::all()->sortBy('gender_name')->lists('gender_name', 'gender_code')->prepend('',''),
							'relation' => Relationship::all()->sortBy('relation_name')->lists('relation_name', 'relation_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
			$patient_dependant = PatientDependant::find($id);
			$dependant = Dependant::findOrFail($patient_dependant->dependant_id);
			return view('dependants.destroy', [
					'dependant'=>$dependant
			]);

	}
	public function destroy($id)
	{	
			$patient_dependant = PatientDependant::find($id);
			PatientDependant::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/dependants?patient_id='.$patient_dependant->patient_id);
	}
	
	public function search(Request $request)
	{
			$patients = DB::table('patients')
					->where('patient_id','<>',$request->patient_id)
					->where(function ($query) use ($request) {
							$query->where('patient_name','like','%'.$request->search.'%')
								  ->orWhere('patient_id', 'like','%'.$request->search.'%');
					})
					->orderBy('patient_name')
					->paginate($this->paginateValue);

			return view('dependants.search', [
					'patients'=>$patients,
					'search'=>$request->search,
					'patient_id'=>$request->patient_id,
					]);
	}

	public function searchById($id)
	{
			$dependants = DB::table('patients')
					->where('patient_id','=',$id)
					->paginate($this->paginateValue);

			return view('dependants.search', [
					'dependants'=>$dependants
			]);
	}

	public function add($dependant_id, $patient_id)
	{

			$patientDependant = new PatientDependant();
			$patientDependant->patient_id = $patient_id;
			$patientDependant->dependant_id = $dependant_id;
			$patientDependant->save();

			$patientDependant = new PatientDependant();
			$patientDependant->patient_id = $dependant_id;
			$patientDependant->dependant_id = $patient_id;
			$patientDependant->save();

			return redirect('/dependant/search?patient_id='.$patient_id);
	}

}
