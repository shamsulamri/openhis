<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Admission;
use App\Encounter;
use Log;
use DB;
use Session;
use App\Bed;
use App\Diet;
use App\DietTexture;
use App\DietClass;
use App\Referral;
use App\AdmissionType;
use App\Consultation;
use App\User;
use Auth;
use App\Ward;
use App\WardHelper;
use App\EncounterHelper;
use App\DojoUtility;

class AdmissionController extends Controller
{
	public $paginateValue=10;


	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index(Request $request)
	{
			$ward_code = "";
			$ward = null;
			$setWard=null;
			if (Auth::user()->can('module-ward')) {
					if (empty($request->cookie('ward'))) {
							Session::flash('message', 'Ward not set. Please select your ward.');
							return redirect('/wards');
					}

					$ward_code = $request->cookie('ward');
					$setWard = $request->cookie('ward');
					$ward = Ward::where('ward_code', $ward_code)->first();
			} 

			$selectFields = ['bed_name', 'a.admission_id','c.patient_id','patient_name','a.encounter_id','a.user_id','e.discharge_id', 
					'f.discharge_id as ward_discharge',
					'a.created_at',
					'arrival_id',	
					'patient_mrn',
					'ward_name',
					'room_name',
					'a.user_id',
					'k.name',
					'diet_name',
					'class_name',
			];

			$admissions = DB::table('admissions as a')
					->select($selectFields)
					->leftJoin('encounters as b', 'b.encounter_id','=', 'a.encounter_id')
					->join('patients as c', 'c.patient_id','=', 'b.patient_id')
					->leftJoin('discharges as e', 'e.encounter_id','=', 'a.encounter_id')
					->leftJoin('ward_discharges as f', 'f.encounter_id','=', 'a.encounter_id')
					->leftJoin('ward_arrivals as g', 'g.encounter_id', '=', 'a.encounter_id')
					->leftJoin('beds as h', 'h.bed_code', '=', 'a.bed_code')
					->leftJoin('wards as i', 'i.ward_code', '=', 'h.ward_code')
					->leftJoin('ward_rooms as j', 'j.room_code', '=', 'h.room_code')
					->leftJoin('users as k', 'k.id', '=', 'a.user_id')
					->leftJoin('diets as l', 'l.diet_code', '=', 'a.diet_code')
					->leftJoin('diet_classes as m', 'm.class_code', '=', 'a.class_code')
					->where('h.ward_code','like', '%'.$ward_code.'%')
					->whereNull('f.encounter_id');
					
			if (Auth::user()->can('module-patient')) {
					$admissions = $admissions->orderBy('patient_name');
			} else {
					$admissions = $admissions->orderBy('b.encounter_id')->orderBy('a.bed_code');
			}

			$admissions = $admissions->paginate($this->paginateValue);

			$wardHelper = null;
			if ($ward) $wardHelper = new WardHelper($ward->ward_code);

			return view('admissions.index', [
					'admissions'=>$admissions,
					'user'=>Auth::user(),
					'wards' => Ward::all()->sortBy('ward_name')->lists('ward_name', 'ward_code')->prepend('',''),
					'admission_type' => AdmissionType::where('admission_code','<>','observe')->orderBy('admission_name')->lists('admission_name', 'admission_code')->prepend('',''),
					'ward' => $ward, 
					'setWard' => $setWard, 
					'dojo' => new DojoUtility(),
					'admission_code'=>null,
					'wardHelper'=> $wardHelper,
			]);
	}

	public function create(Request $request)
	{
			$admission = new Admission();
			$admission->encounter_id = $request->encounter_id;
			$encounter = Encounter::findOrFail($admission->encounter_id);

			$consultants = User::leftjoin('user_authorizations as a','a.author_id', '=', 'users.author_id')
							->where('module_consultation',1)
							->orderBy('name')
							->lists('name','id')
							->prepend('','');

			return view('admissions.create', [
					'admission' => $admission,
					'patient' => $encounter->patient,
					'consultant' => $consultants,
					'bed' => Bed::where('encounter_code',$encounter->encounter_code)->orderBy('bed_name')->lists('bed_name', 'bed_code')->prepend('',''),
					'diet' => Diet::all()->sortBy('diet_name')->lists('diet_name', 'diet_code')->prepend('',''),
					'texture' => DietTexture::all()->sortBy('texture_name')->lists('texture_name', 'texture_code')->prepend('',''),
					'class' => DietClass::all()->sortBy('class_name')->lists('class_name', 'class_code')->prepend('',''),
					'referral' => Referral::all()->sortBy('referral_name')->lists('referral_name', 'referral_code')->prepend('',''),
					'admission_type' => AdmissionType::where('admission_code','<>','observe')->orderBy('admission_name')->lists('admission_name', 'admission_code')->prepend('',''),
					'encounter' => $encounter,
					]);
	}

	public function store(Request $request) 
	{
			$admission = new Admission();
			$valid = $admission->validate($request->all(), $request->_method);

			$ward = Ward::all()->first();

			if ($valid->passes()) {
					$admission = new Admission($request->all());
					$admission->diet_code='normal';
					$admission->admission_id = $request->admission_id;
					$admission->save();
					if ($admission->encounter->encounter_code=='daycare') {
							$ward = Ward::where('ward_code','=','daycare')->first();
					}
					Session::flash('message', 'Record successfully created.');
					return redirect('/admission_beds/'.$admission->admission_id.'/'.$ward->ward_code);
			} else {
					return redirect('/admissions/create?encounter_id='.$request->encounter_id)
							->withErrors($valid)
							->withInput();
			}
	}

	public function show($id)
	{
			$admission = Admission::findOrFail($id);

			return view('admissions.view', [
					'admission'=>$admission,
					'patient'=>$admission->encounter->patient,
			]);
	}

	public function edit($id) 
	{
			$admission = Admission::findOrFail($id);
			$encounter = Encounter::findOrFail($admission->encounter_id);

			return view('admissions.edit', [
					'admission'=>$admission,
					'patient'=>$encounter->patient,
					'consultant' => User::orderBy('name')->lists('name', 'id')->prepend('',''),
					'bed' => Bed::where('encounter_code',$encounter->encounter_code)->orderBy('bed_name')->lists('bed_name', 'bed_code')->prepend('',''),
					'diet' => Diet::all()->sortBy('diet_name')->lists('diet_name', 'diet_code')->prepend('',''),
					'texture' => DietTexture::all()->sortBy('texture_name')->lists('texture_name', 'texture_code')->prepend('',''),
					'class' => DietClass::all()->sortBy('class_name')->lists('class_name', 'class_code')->prepend('',''),
					'referral' => Referral::all()->sortBy('referral_name')->lists('referral_name', 'referral_code')->prepend('',''),
					'admission_type' => AdmissionType::all()->sortBy('admission_name')->lists('admission_name', 'admission_code')->prepend('',''),
					'encounter' => $encounter,
					]);
	}

	public function update(Request $request, $id) 
	{
			$admission = Admission::findOrFail($id);
			$admission->fill($request->input());
			$admission->admission_nbm = $request->admission_nbm ?: 0;
			$encounter = Encounter::findOrFail($admission->encounter_id);

			if ($request->consultation_id>0) {
					$admission->diet_code = $request->diet_code;
					$admission->texture_code = $request->texture_code;
					$admission->class_code = $request->class_code;
					$admission->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/diet');
			} else {

					$method = $request->_method;
					$valid = $admission->validate($request->all(), $method);	

					if ($valid->passes()) {
							$admission->save();
							//Session::flash('message', 'Record successfully updated.');
							//return redirect('/admissions');
							if ($admission->bed) {
								return redirect('/admissions');
							} else {
								return redirect('/admission_beds?admission_id='.$admission->admission_id);
							}
					} else {
							return view('admissions.edit', [
									'admission'=>$admission,
									'patient' => $admission->encounter->patient,
									'bed' => Bed::all()->sortBy('bed_name')->lists('bed_name', 'bed_code')->prepend('',''),
									'diet' => Diet::all()->sortBy('diet_name')->lists('diet_name', 'diet_code')->prepend('',''),
									'texture' => DietTexture::all()->sortBy('texture_name')->lists('texture_name', 'texture_code')->prepend('',''),
									'class' => DietClass::all()->sortBy('class_name')->lists('class_name', 'class_code')->prepend('',''),
									'referral' => Referral::all()->sortBy('referral_name')->lists('referral_name', 'referral_code')->prepend('',''),
									'admission_type' => AdmissionType::all()->sortBy('admission_name')->lists('admission_name', 'admission_code')->prepend('',''),
									'consultant' => User::orderBy('name')->lists('name', 'id')->prepend('',''),
									'encounter' => $encounter,
									])
									->withErrors($valid);			
					}
			}
	}
	
	public function diet() 
	{
			$consultation=Consultation::find(Session::get('consultation_id'));
			$encounter = $consultation->encounter;
			$admission = $consultation->encounter->admission;
			return view('admissions.diet', [
					'admission'=>$admission,
					'consultation'=>$consultation,
					'patient'=>$consultation->encounter->patient,
					'consultOption'=>'dietary', 
					'diet' => Diet::all()->sortBy('diet_name')->lists('diet_name', 'diet_code')->prepend('',''),
					'texture' => DietTexture::all()->sortBy('texture_name')->lists('texture_name', 'texture_code')->prepend('',''),
					'class' => DietClass::all()->sortBy('class_name')->lists('class_name', 'class_code')->prepend('',''),
					'consultOption' => 'diet',
					]);
	}

	public function delete($id)
	{
		$admission = Admission::findOrFail($id);
		return view('admissions.destroy', [
			'admission'=>$admission
			]);

	}
	public function destroy($id)
	{	
			Admission::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/admissions');
	}
	
	public function search(Request $request)
	{
			$ward = $request->ward;
			$setWard = $request->cookie('ward');

			$selectFields = ['bed_name', 'a.admission_id','c.patient_id','patient_name','d.consultation_id','a.encounter_id','a.user_id','e.discharge_id', 
					'f.discharge_id as ward_discharge',
					'a.created_at',
					'arrival_id',	
					'patient_mrn',
					'ward_name',
					'room_name',
					'a.user_id',
					'k.name',
			];
			$admissions = DB::table('admissions as a')
					->select($selectFields)
					->leftJoin('encounters as b', 'b.encounter_id','=', 'a.encounter_id')
					->join('patients as c', 'c.patient_id','=', 'b.patient_id')
					->leftJoin('consultations as d', 'd.encounter_id','=', 'a.encounter_id')
					->leftJoin('discharges as e', 'e.encounter_id','=', 'a.encounter_id')
					->leftJoin('ward_discharges as f', 'f.encounter_id','=', 'a.encounter_id')
					->leftJoin('ward_arrivals as g', 'g.encounter_id', '=', 'a.encounter_id')
					->leftJoin('beds as h', 'h.bed_code', '=', 'a.bed_code')
					->leftJoin('wards as i', 'i.ward_code', '=', 'h.ward_code')
					->leftJoin('ward_rooms as j', 'j.room_code', '=', 'h.room_code')
					->leftJoin('users as k', 'k.id', '=', 'a.user_id');

			if (!empty($request->search)) {
					$admissions = $admissions
									->where('patient_name','like', '%'.$request->search.'%')
									->orWhere('patient_mrn','like', '%'.$request->search.'%');
			}
					
			if (!empty($request->ward)) {
					$admissions = $admissions->where('h.ward_code','=', $request->ward);
			}

			if (!empty($request->admission_code)) {
					$admissions = $admissions->where('admission_code','=', $request->admission_code);
			}

			$admissions = $admissions
					->whereNull('f.encounter_id')
					->groupBy('b.encounter_id')
					->orderBy('patient_name')
					->orderBy('a.bed_code');
					
			//dd($admissions->toSql());

			$admissions = $admissions->paginate($this->paginateValue);

			$wardHelper = null;
			$ward_code = $request->cookie('ward');
			if ($ward) $wardHelper = new WardHelper($ward_code);

			return view('admissions.index', [
					'admissions'=>$admissions,
					'user'=>Auth::user(),
					'wards' => Ward::all()->sortBy('ward_name')->lists('ward_name', 'ward_code')->prepend('',''),
					'ward' => $request->ward,
					'admission_type' => AdmissionType::where('admission_code','<>','observe')->orderBy('admission_name')->lists('admission_name', 'admission_code')->prepend('',''),
					'search'=>$request->search,
					'setWard'=>$setWard,
					'dojo' => new DojoUtility(),
					'admission_code'=>$request->admission_code,
					'wardHelper'=>$wardHelper,
			]);
	}

	public function searchById($id)
	{
			$admissions = DB::table('admissions')
					->where('admission_id','=',$id)
					->paginate($this->paginateValue);

			return view('admissions.index', [
					'admissions'=>$admissions
			]);
	}



}
