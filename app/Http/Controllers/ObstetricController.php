<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Log;
use DB;
use Session;
use Gate;
use App\Consultation;

class ObstetricController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function history()
	{
			$consultation = Consultation::find(Session::get('consultation_id'));
			return view('obstetric_histories.history', [
					'consultation'=>$consultation,
					'patient'=>$consultation->encounter->patient,
			]);
	}

	public function update(Request $request)
	{
			$consultation = Consultation::find(Session::get('consultation_id'));
			$patient = $consultation->encounter->patient;


			$patient->patient_gravida = $request->patient_gravida;
			$patient->patient_parity = $request->patient_parity;
			$patient->patient_parity_plus = $request->patient_parity_plus;
			$patient->patient_lnmp = $request->patient_lnmp;
			$patient->save();

			Session::flash('message', 'Record successfully updated.');
			return redirect('/obstetric');
	}
}
