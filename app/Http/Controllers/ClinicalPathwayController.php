<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Log;
use DB;
use Session;
use App\Consultation;

class ClinicalPathwayController extends Controller
{
	public function index()
	{
			$consultation = Consultation::find(Session::get('consultation_id'));
			return view("clinical_pathway.cp",[
					'consultation' => $consultation,
					'patient' => $consultation->encounter->patient,
			]);
	}
}
