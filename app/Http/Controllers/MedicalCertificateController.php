<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\MedicalCertificate;
use Log;
use DB;
use Session;
use App\Consultation;
use Carbon\Carbon;
use App\DojoUtility;

class MedicalCertificateController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$medical_certificates = DB::table('medical_certificates')
					->orderBy('encounter_id')
					->paginate($this->paginateValue);
			return view('medical_certificates.index', [
					'medical_certificates'=>$medical_certificates
			]);
	}

	public function create()
	{
			$consultation = Consultation::find(Session::get('consultation_id'));
			$medical_certificate = MedicalCertificate::where('encounter_id','=',$consultation->encounter->encounter_id)->get();
			
			if (count($medical_certificate)>0) {
					return redirect('/medical_certificates/'.$medical_certificate[0]->mc_id.'/edit');
			}

			$medical_certificate = new MedicalCertificate();
			$medical_certificate->encounter_id = $consultation->encounter->encounter_id;
			$today = date('d/m/Y', strtotime(Carbon::now()));  

			$medical_certificate->mc_start = $today;

			return view('medical_certificates.create', [
					'medical_certificate' => $medical_certificate,
					'consultation' => $consultation,
					'patient' => $consultation->encounter->patient,
					'consultOption' => 'medical_certificate',
					'minYear' => Carbon::now()->year,
					]);
	}

	public function store(Request $request) 
	{
			$consultation = Consultation::find($request->consultation_id);
			$medical_certificate = new MedicalCertificate();
			$valid = $medical_certificate->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$medical_certificate = new MedicalCertificate($request->all());
					$medical_certificate->mc_id = $request->mc_id;
					$medical_certificate->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/medical_certificates/'.$medical_certificate->mc_id.'/edit');
			} else {
					return redirect('/medical_certificates/create?consultation_id='.$consultation->consultation_id)
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$medical_certificate = MedicalCertificate::findOrFail($id);
			$consultation = Consultation::find($medical_certificate->consultation_id);
			return view('medical_certificates.edit', [
					'medical_certificate'=>$medical_certificate,
					'consultation' => $consultation,
					'patient' => $consultation->encounter->patient,
					'consultOption' => 'medical_certificate',
					'minYear' => Carbon::now()->year,
					]);
	}

	public function update(Request $request, $id) 
	{
			$medical_certificate = MedicalCertificate::findOrFail($id);
			$medical_certificate->fill($request->input());


			$valid = $medical_certificate->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$medical_certificate->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/medical_certificates/'.$id.'/edit');
			} else {
					$consultation = $medical_certificate->consultation;
					return view('medical_certificates.edit', [
							'medical_certificate'=>$medical_certificate,
							'consultation' => $consultation,
							'patient' => $consultation->encounter->patient,
							'consultOption' => 'medical_certificate',
							'minYear' => Carbon::now()->year,
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$medical_certificate = MedicalCertificate::findOrFail($id);
		return view('medical_certificates.destroy', [
			'medical_certificate'=>$medical_certificate
			]);

	}
	public function destroy($id)
	{	
			MedicalCertificate::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/medical_certificates');
	}
	
	public function search(Request $request)
	{
			$medical_certificates = DB::table('medical_certificates')
					->where('encounter_id','like','%'.$request->search.'%')
					->orWhere('mc_id', 'like','%'.$request->search.'%')
					->orderBy('encounter_id')
					->paginate($this->paginateValue);

			return view('medical_certificates.index', [
					'medical_certificates'=>$medical_certificates,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$medical_certificates = DB::table('medical_certificates')
					->where('mc_id','=',$id)
					->paginate($this->paginateValue);

			return view('medical_certificates.index', [
					'medical_certificates'=>$medical_certificates
			]);
	}
}
