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
use App\Form;
use App\FormValue;
use App\FormHelper;
use App\Team;
use App\Period;
use App\DietHelper;

class AdmissionController extends Controller
{
	public $paginateValue=10;

	public $selectFields = ['bed_name', 'a.admission_id','c.patient_id','patient_name','a.encounter_id','a.user_id','e.discharge_id', 
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
					'n.team_name',
					'nbm_status',
			];


	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index(Request $request)
	{
			DietHelper::updateNilByMouthStatus();
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

			$admissions = DB::table('admissions as a')
					->select($this->selectFields)
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
					->leftJoin('teams as n', 'n.team_code', '=', 'a.team_code')
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
					$admission->class_code=$ward->class_diet;
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

	/**
	public function show($id)
	{
			$admission = Admission::findOrFail($id);

			$sql = sprintf("
				select a.form_code, form_name, result_count, b.updated_at
				from forms a
				left join (select form_code, count(form_code) as result_count, updated_at from form_values where encounter_id=%d group by form_code) b on (b.form_code = a.form_code)
				where result_count>0
				order by result_count desc, form_name
				", $admission->encounter_id);

			Log::info($sql);
			$results = DB::select($sql);

			$form_codes = FormValue::distinct()
								->where('encounter_id','=', $admission->encounter_id)
								->get(['form_code']);


			$forms = DB::table('forms')
					->orderBy('form_name')
					->whereNotIn('form_code', $form_codes->pluck('form_code'))
					->paginate($this->paginateValue);

			return view('admissions.view', [
					'admission'=>$admission,
					'patient'=>$admission->encounter->patient,
					'forms'=>$forms,
					'results'=>$results,
					'formHelper'=>new FormHelper(),
			]);
	}

	public function searchForm(Request $request)
	{
			$admission = Admission::findOrFail($request->admission_id);

			$sql = sprintf("
				select a.form_code, form_name, result_count, b.updated_at
				from forms a
				left join (select form_code, count(form_code) as result_count, updated_at from form_values where encounter_id=%d group by form_code) b on (b.form_code = a.form_code)
				where result_count>0
				order by result_count desc, form_name
				", $admission->encounter_id);

			Log::info($sql);
			$results = DB::select($sql);

			$form_codes = FormValue::distinct()
								->where('encounter_id','=', $admission->encounter_id)
								->get(['form_code']);


			$forms = DB::table('forms')
					->where('form_name','like','%'.$request->search.'%')
					->orWhere('form_code', 'like','%'.$request->search.'%')
					->orderBy('form_name')
					->whereNotIn('form_code', $form_codes->pluck('form_code'))
					->paginate($this->paginateValue);

			return view('admissions.view', [
					'admission'=>$admission,
					'patient'=>$admission->encounter->patient,
					'forms'=>$forms,
					'results'=>$results,
					'search'=>$request->search,
			]);
	}
	**/

	public function edit($id) 
	{
			$admission = Admission::findOrFail($id);
			$encounter = Encounter::findOrFail($admission->encounter_id);

			$consultants = User::leftjoin('user_authorizations as a','a.author_id', '=', 'users.author_id')
							->where('module_consultation',1)
							->orderBy('name')
							->lists('name','id')
							->prepend('','');

			return view('admissions.edit', [
					'admission'=>$admission,
					'patient'=>$encounter->patient,
					'consultant' => $consultants,
					'bed' => Bed::where('encounter_code',$encounter->encounter_code)->orderBy('bed_name')->lists('bed_name', 'bed_code')->prepend('',''),
					'diet' => Diet::all()->sortBy('diet_name')->lists('diet_name', 'diet_code')->prepend('',''),
					'texture' => DietTexture::all()->sortBy('texture_name')->lists('texture_name', 'texture_code')->prepend('',''),
					'class' => DietClass::all()->sortBy('class_name')->lists('class_name', 'class_code')->prepend('',''),
					'referral' => Referral::all()->sortBy('referral_name')->lists('referral_name', 'referral_code')->prepend('',''),
					'admission_type' => AdmissionType::all()->sortBy('admission_name')->lists('admission_name', 'admission_code')->prepend('',''),
					'encounter' => $encounter,
					'teams' => Team::all()->sortBy('team_name')->lists('team_name', 'team_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$admission = Admission::findOrFail($id);
			$admission->fill($request->input());
			$admission->nbm_status = $request->nbm_status ?: 0;
			$encounter = Encounter::findOrFail($admission->encounter_id);

			$nbm_datetime = DojoUtility::dateWriteFormat($request->nbm_start_date).' '.$request->nbm_start_time;

			if ($request->consultation_id>0) {
					$admission->diet_code = $request->diet_code;
					$admission->texture_code = $request->texture_code;
					$admission->class_code = $request->class_code;
					$admission->nbm_datetime = $nbm_datetime;
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
							return redirect('/admissions/'.$admission->admission_id.'/edit')
									->withErrors($valid)
									->withInput();
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
					'nbm_start_time'=>'10:05',
					'period' => Period::whereIn('period_code', array('hour','day'))->orderBy('period_name')->lists('period_name', 'period_code')->prepend('',''),
					'nbm_start_date' => DojoUtility::dateReadFormat($admission->nbm_datetime),
					'nbm_start_time' => DojoUtility::timeReadFormat($admission->nbm_datetime),
					]);
	}

	public function removeNilByMouth($admission_id)
	{
			DB::table('admissions')->where('admission_id', $admission_id)->update(['nbm_status'=>0, 
					'nbm_duration'=>null,
					'nbm_datetime'=>null, 
					'period_code'=>null
			]);

			Session::flash('message', 'Nil by Mouth cleared.');
			return redirect('/diet');
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

			/**
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
			**/
			$admissions = DB::table('admissions as a')
					->select($this->selectFields)
					->leftJoin('encounters as b', 'b.encounter_id','=', 'a.encounter_id')
					->join('patients as c', 'c.patient_id','=', 'b.patient_id')
					->leftJoin('consultations as d', 'd.encounter_id','=', 'a.encounter_id')
					->leftJoin('discharges as e', 'e.encounter_id','=', 'a.encounter_id')
					->leftJoin('ward_discharges as f', 'f.encounter_id','=', 'a.encounter_id')
					->leftJoin('ward_arrivals as g', 'g.encounter_id', '=', 'a.encounter_id')
					->leftJoin('beds as h', 'h.bed_code', '=', 'a.bed_code')
					->leftJoin('wards as i', 'i.ward_code', '=', 'h.ward_code')
					->leftJoin('ward_rooms as j', 'j.room_code', '=', 'h.room_code')
					->leftJoin('users as k', 'k.id', '=', 'a.user_id')
					->leftJoin('diets as l', 'l.diet_code', '=', 'a.diet_code')
					->leftJoin('diet_classes as m', 'm.class_code', '=', 'a.class_code')
					->leftJoin('teams as n', 'n.team_code', '=', 'a.team_code');

			if (!empty($request->search)) {
					$admissions = $admissions->where(function ($query) use ($request) {
							$query->where('patient_name','like', '%'.$request->search.'%')
									->orWhere('patient_mrn','like', '%'.$request->search.'%');
					});
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
