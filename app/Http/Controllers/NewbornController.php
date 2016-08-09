<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Newborn;
use Log;
use DB;
use Session;
use App\DeliveryMode as Delivery;
use App\BirthComplication as Complication;
use App\BirthType as Birth;
use App\Consultation;
use App\Gender;
use App\Patient;
use Carbon\Carbon;
use App\DojoUtility;
use App\PatientDependant;

class NewbornController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$consultation = Consultation::find(Session::get('consultation_id'));
			$newborns = Newborn::where('encounter_id', $consultation->encounter->encounter_id)
					->orderBy('encounter_id')
					->paginate($this->paginateValue);
			return view('newborns.index', [
					'newborns'=>$newborns,
					'consultation' => $consultation,
					'patient' => $consultation->encounter->patient,
					'consultOption' => 'newborn',
			]);
	}

	public function create(Request $request)
	{
			$consultation = Consultation::find($request->id);
			$newborn = new Newborn();
			$newborn->encounter_id = $consultation->encounter->encounter_id;
			$patient_newborn = new Patient();
			$patient_newborn->patient_birthdate=DojoUtility::today();
			$patient_newborn->patient_birthtime=DojoUtility::timenow();

			return view('newborns.create', [
					'newborn' => $newborn,
					'delivery' => Delivery::all()->sortBy('delivery_name')->lists('delivery_name', 'delivery_code')->prepend('',''),
					'complication' => Complication::all()->sortBy('complication_name')->lists('complication_name', 'complication_code')->prepend('',''),
					'birth' => Birth::all()->sortBy('birth_name')->lists('birth_name', 'birth_code')->prepend('',''),
					'consultation' => $consultation,
					'patient' => $consultation->encounter->patient,
					'consultOption' => 'newborn',
					'minYear' => Carbon::now()->year,
					'apgar_values'=> array(0=>'0',1=>'1',2=>'2'),
					'gender' => Gender::all()->sortBy('gender_name')->lists('gender_name', 'gender_code')->prepend('',''),
					'today' =>date('d/m/Y', strtotime(Carbon::now())),
					'patient_newborn' => $patient_newborn,
					]);
	}

	public function store(Request $request) 
	{
			$consultation = Consultation::find(Session::get('consultation_id'));
			$newborn = new Newborn();
			$valid = $newborn->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$patient = new Patient();
					$patient->patient_name = "B/O ". $consultation->encounter->patient->patient_name;
					$patient->patient_birthdate = $request->patient_birthdate;
					$patient->patient_birthtime = $request->patient_birthtime;
					$patient->gender_code = $request->gender_code;
					$patient->save();

					$dependant = new PatientDependant();
					$dependant->patient_id = $patient->patient_id;
					$dependant->dependant_id = $consultation->encounter->patient->patient_id;
					$dependant->relation_code = '3';
					$dependant->save();

					$newborn = new Newborn($request->all());
					$newborn->newborn_id = $request->newborn_id;
					$newborn->patient_id = $patient->patient_id;
					$newborn->save();


					Session::flash('message', 'Record successfully created.');
					return redirect('/newborns?id='.$request->consultation_id);
			} else {
					return redirect('/newborns/create?id='.$request->consultation_id)
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit(Request $request, $id) 
	{
			$consultation = Consultation::find($request->consultation_id);
			$newborn = Newborn::findOrFail($id);
			$patient_newborn = Patient::find($newborn->patient_id);

			return view('newborns.edit', [
					'newborn'=>$newborn,
					'delivery' => Delivery::all()->sortBy('delivery_name')->lists('delivery_name', 'delivery_code')->prepend('',''),
					'complication' => Complication::all()->sortBy('complication_name')->lists('complication_name', 'complication_code')->prepend('',''),
					'birth' => Birth::all()->sortBy('birth_name')->lists('birth_name', 'birth_code')->prepend('',''),
					'consultation' => $consultation,
					'patient' => $consultation->encounter->patient,
					'consultOption' => 'newborn',
					'gender' => Gender::all()->sortBy('gender_name')->lists('gender_name', 'gender_code')->prepend('',''),
					'apgar_values'=> array(0=>'0',1=>'1',2=>'2'),
					'minYear' => Carbon::now()->year,
					'patient_newborn' => $patient_newborn,
					'today' =>date('d/m/Y', strtotime(Carbon::now())),
					]);
	}

	public function update(Request $request, $id) 
	{
			$newborn = Newborn::findOrFail($id);
			$newborn->fill($request->input());

			$valid = $newborn->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$newborn->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/newborns?id='.$request->consultation_id);
			} else {
					return view('newborns.edit', [
							'newborn'=>$newborn,
							'delivery' => Delivery::all()->sortBy('delivery_name')->lists('delivery_name', 'delivery_code')->prepend('',''),
							'complication' => Complication::all()->sortBy('complication_name')->lists('complication_name', 'complication_code')->prepend('',''),
							'birth' => Birth::all()->sortBy('birth_name')->lists('birth_name', 'birth_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$newborn = Newborn::findOrFail($id);
		return view('newborns.destroy', [
				'newborn'=>$newborn,
				'patient'=>$newborn->encounter->patient,

			]);

	}
	public function destroy($id)
	{	
			$newborn = Newborn::find($id);
			Newborn::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/newborns');
	}
	
	public function search(Request $request)
	{
			$newborns = DB::table('enc_newborns')
					->where('encounter_id','like','%'.$request->search.'%')
					->orWhere('newborn_id', 'like','%'.$request->search.'%')
					->orderBy('encounter_id')
					->paginate($this->paginateValue);

			return view('newborns.index', [
					'newborns'=>$newborns,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$newborns = DB::table('enc_newborns')
					->where('newborn_id','=',$id)
					->paginate($this->paginateValue);

			return view('newborns.index', [
					'newborns'=>$newborns
			]);
	}
}
