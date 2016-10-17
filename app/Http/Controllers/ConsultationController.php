<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Consultation;
use Log;
use DB;
use Session;
use Auth;
use App\Patient;
use App\Order;
use App\OrderPost;
use App\Encounter;
use App\DojoUtility;

class ConsultationController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$consultations = Consultation::where('user_id', Auth::user()->id)
					->orderBy('created_at','desc');
				

			//dd($consultations->toSql());
			$consultations = $consultations->paginate($this->paginateValue);

			$consultations = DB::table('consultations as a')
					->leftjoin('patients as b','b.patient_id','=', 'a.patient_id')
					->orderBy('a.created_at', 'desc')
					->paginate($this->paginateValue);

			return view('consultations.index', [
					'consultations'=>$consultations,
					'dojo'=>new DojoUtility(),
					'consult'=>new Consultation(),
			]);
	}

	public function progress($consultation_id) {
			$consultation = Consultation::find($consultation_id);
			$notes = Consultation::where('patient_id', $consultation->patient_id)
					->orderBy('created_at','desc')
					->paginate($this->paginateValue);
			return view('consultations.progress', [
					'notes'=>$notes,
					'consultation'=>$consultation,
					'patient'=>$consultation->encounter->patient,
					'consultOption' => 'consultation',
			]);
	}

	public function create(Request $request)
	{
			$encounter = Encounter::find($request->encounter_id);

			$consultation = DB::select("select consultation_id, encounter_id from consultations 
					where user_id = ".Auth::user()->id."
					and consultation_status=0
					and encounter_id = ".$request->encounter_id);

			//if ($encounter->encounter_code!='inpatient') {
					$consultation = DB::select("select consultation_id, encounter_id from consultations 
							where user_id = ".Auth::user()->id."
							and consultation_status=1
							and encounter_id = ".$request->encounter_id);

			//}

			if (!empty($consultation)) {
					$consultation_id = $consultation[0]->consultation_id;
			}


			if (empty($consultation)==true) {
					Log::info("New consultation");
					$consultation = new Consultation();
					$consultation->user_id = Auth::user()->id;
					$consultation->encounter_id = $request->encounter_id;
					$consultation->patient_id = $encounter->patient_id;
					$consultation->consultation_status=1;
					$consultation->save();
					Session::set('consultation_id', $consultation->consultation_id);
					Session::set('encounter_id', $encounter->encounter_id);
					return view('consultations.edit', [
						'consultation'=>$consultation,
						'patient'=>$consultation->encounter->patient,
						'tab'=>'clinical',
						'consultOption'=>'consultation',
					]);
			} else {
					Log::info("Edit consultation");
					$consultation = Consultation::find($consultation[0]->consultation_id);
					return view('consultations.edit', [
						'consultation'=>$consultation,
						'tab'=>'clinical',
						'patient'=>$encounter->patient,
					]);
			};
	}

	public function store(Request $request) 
	{
			$consultation = new Consultation();
			$valid = $consultation->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$consultation = new Consultation($request->all());
					$consultation->consultation_id = $request->consultation_id;
					$consultation->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/consultations/id/'.$consultation->consultation_id);
			} else {
					return redirect('/consultations/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function close()
	{
			$id = Session::get('consultation_id');
			$consultation = Consultation::findOrFail($id);
			if ($consultation->encounter->encounter_code=='outpatient') {
					$consultation->consultation_status = 1;
			} else {
					$consultation->consultation_status = 2;
			}
			$consultation->save();

			$post = new OrderPost();
			$post->consultation_id = $id;
			$post->save();

			Order::where('consultation_id','=',$id)
					->where('post_id','=',0)
					->update(['post_id'=>$post->post_id]);

			if (Auth::user()->authorization->module_consultation==1) {
					return redirect('/patient_lists');
			} else {
					return redirect('/order_queues');
			}
	}

	public function edit($id) 
	{
			$consultation = Consultation::findOrFail($id);
			Session::set('consultation_id', $consultation->consultation_id);
			Session::set('encounter_id', $consultation->encounter->encounter_id);
			return view('consultations.edit', [
					'consultation'=>$consultation,
					'patient'=>$consultation->encounter->patient,
					'tab'=>'clinical',
					'consultOption'=>'consultation',
					'admission'=>$consultation->encounter->admission,
					]);
	}

	public function update(Request $request, $id) 
	{
			$consultation = Consultation::findOrFail($id);
			$consultation->fill($request->input());


			$valid = $consultation->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$consultation->save();
					//Session::flash('message', 'Record successfully updated.');
					//return redirect('/consultations/'.$id.'/edit');
					return redirect('/consultation_diagnoses');
			} else {
					return view('consultations.edit', [
							'consultation'=>$consultation,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$consultation = Consultation::findOrFail($id);
		return view('consultations.destroy', [
			'consultation'=>$consultation
			]);

	}
	public function destroy($id)
	{	
			Consultation::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/consultations');
	}
	
	public function search(Request $request)
	{
			$consultations = DB::table('consultations as a')
					->leftjoin('patients as b','b.patient_id','=', 'a.patient_id')
					->where('patient_name','like','%'.$request->search.'%')
					->orWhere('patient_mrn', 'like','%'.$request->search.'%')
					->orderBy('consultation_status')
					->paginate($this->paginateValue);

			return view('consultations.index', [
					'consultations'=>$consultations,
					'search'=>$request->search,
					'consult'=>new Consultation(),
					'dojo'=>new DojoUtility(),
					]);
	}

	public function searchById($id)
	{
			$consultations = DB::table('consultations')
					->where('consultation_id','=',$id)
					->paginate($this->paginateValue);

			return view('consultations.index', [
					'consultations'=>$consultations
			]);
	}
}
