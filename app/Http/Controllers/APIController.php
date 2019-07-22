<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Log;
use DB;
use Session;
use Gate;
use App\Patient;
use App\MedicalAlert;
use App\PatientDependant;
use Carbon\Carbon;

class APIController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			//$this->middleware('auth');
	}

	public function patient($mrn)
	{
			$patient = Patient::select(
						'patient_name as name',
						'patient_birthdate as birthdate',
						'gender_code as gender',
						'patient_email as email',
						'patient_phone_home as phone_number',
						'patient_phone_mobile as phone_mobile',
						'patient_new_ic as ic_number'
				)->where('patient_mrn','=',$mrn)->first();

			return $patient;
	}

	public function dependant($mrn)
	{
			$result = DB::table('patient_dependants as a')
						->select(
						'c.patient_name as name',
						'c.patient_birthdate as birthdate',
						'c.gender_code as gender',
						'c.patient_email as email',
						'c.patient_phone_home as phone_number',
						'c.patient_phone_mobile as phone_mobile',
						'c.patient_new_ic as ic_number',
						'relation_name as relationship'
						)
						->leftJoin('patients as b', 'b.patient_id', '=', 'a.patient_id')
						->leftJoin('patients as c', 'c.patient_id', '=', 'a.dependant_id')
						->leftJoin('ref_relationships as d', 'd.relation_code', '=', 'a.relation_code')
						->where('b.patient_mrn', '=', $mrn)
						->get();

			return $result;
	}

	public function allergy($mrn)
	{
			$result = DB::table('medical_alerts as a')
						->select('alert_description as description')
						->leftJoin('patients as b', 'a.patient_id', '=', 'b.patient_id')
						->where('patient_mrn', '=', $mrn)
						->where('alert_public', '=', 1)
						->get();
			return $result;
	}

	public function appointment($mrn) 
	{
			$result = DB::table('appointments as a')
						->select('appointment_datetime as datetime', 'service_name as service', 'b.patient_new_ic as ic_number')
						->leftJoin('patients as b', 'a.patient_id', '=', 'b.patient_id')
						->leftJoin('appointment_services as c', 'c.service_id', '=', 'a.service_id')
						->where('patient_mrn', '=', $mrn)
						->where('appointment_datetime', '>=', Carbon::today())
						->get();

			return $result;
	}


	public function discharge($mrn) 
	{
			$result = DB::table('discharges as a')
						->select('discharge_date as date', 'encounter_name as encounter', 'c.patient_new_ic as ic_number')
						->leftJoin('encounters as b', 'b.encounter_id', '=', 'a.encounter_id')
						->leftJoin('patients as c', 'c.patient_id', '=', 'b.patient_id')
						->leftJoin('ref_encounter_types as d', 'd.encounter_code', '=', 'b.encounter_code')
						->where('patient_mrn', '=', $mrn)
						->get();

			return $result;
	}

	public function medication($mrn) 
	{
			$result = DB::table('order_drugs as a')
						->select('product_name as drug', 'b.created_at as date', 'order_description as instruction', 'd.patient_new_ic as ic_number', 'name as prescribed_by')
						->leftJoin('orders as b', 'b.order_id', '=', 'a.order_id')
						->leftJoin('encounters as c', 'c.encounter_id', '=', 'b.encounter_id')
						->leftJoin('patients as d', 'd.patient_id', '=', 'c.patient_id')
						->leftJoin('products as e', 'e.product_code', '=', 'b.product_code')
						->leftJoin('users as f', 'f.id', '=', 'b.user_id')
						->where('patient_mrn', '=', $mrn)
						->get();

			return $result;
	}
}
