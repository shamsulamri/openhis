<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Log;
use DB;
use Session;
use Gate;
use App\FormPosition;
use App\Form;
use App\FormValue;
use App\Encounter;
use App\EncounterHelper;
use App\Patient;
use App\FormHelper;
use App\Consultation;

class FormValueController extends Controller
{
	public $paginateValue=10;

	public function create($form_code, $patient_id)
	{
			$consultation = Consultation::find(Session::get('consultation_id'));
			$properties = FormPosition::where('form_code', '=', $form_code)
					->orderBy('property_position')
					->get();
			$form = Form::find($form_code);
			$patient = Patient::find($patient_id);
			$encounter_id = EncounterHelper::getActiveEncounter($patient_id)->encounter_id;

			return view('form_values.create', [
					'properties' => $properties,
					'form' => $form,
					'encounter_id' => $encounter_id,
					'json'=>null,
					'value_id'=>null,
					'patient'=>$patient,
					'admission'=>EncounterHelper::getCurrentAdmission($encounter_id),
					'consultation'=>$consultation,
					'is_create'=>True,
			]);
	}

	public function store(Request $request) 
	{
			$form_code = $request->form_code;
			$encounter_id =  $request->encounter_id;
			$encounter = Encounter::find($encounter_id);

			$form_value = new FormValue($request->all());
			if ($request->value_id) {
				$form_value = FormValue::find($request->value_id);
			}

			$properties = FormPosition::where('form_code', '=', $form_code)
					->orderBy('property_position')
					->get();


			$json = "";
			foreach ($properties as $property) {
				$property_code = $property->property_code;
				$json .= '"'.$property->property_code.'":"'.$request[$property_code].'",';
			}
			$json = substr($json,0,-1);
			$json = "{".$json."}";

			$form_value->form_value = $json;
			$form_value->patient_id = $encounter->patient_id;

			$consultation_id = Session::get('consultation_id');
			if ($consultation_id) {
				$form_value->consultation_id = $consultation_id;
			}
			$form_value->save();

			Session::flash('message', 'Record successfully created.');
			return redirect('/form/'.$form_code.'/'.$encounter_id);
	}

	public function edit($id) 
	{
			$consultation = Consultation::find(Session::get('consultation_id'));
			$form_value = FormValue::find($id);
			$json = json_decode($form_value->form_value,true);

			$properties = FormPosition::where('form_code', '=', $form_value->form_code)
					->orderBy('property_position')
					->get();
			$form = Form::find($form_value->form_code);

			$encounter = Encounter::find($form_value->encounter_id);
			Log::info($json);

			return view('form_values.create', [
					'properties' => $properties,
					'form' => $form,
					'encounter_id' => $form_value->encounter_id,
					'value_id' => $form_value->value_id,
					'json' => $json,
					'patient'=>$encounter->patient,
					'admission'=>null,
					'consultation'=>$consultation,
			]);

	}

	public function show($form_code, $encounter_id)
	{
			$consultation = Consultation::find(Session::get('consultation_id'));
			$encounter = Encounter::find($encounter_id);
			$admission = EncounterHelper::getCurrentAdmission($encounter_id);
			$json_values = FormValue::where('encounter_id', '=', $encounter_id)
					->where('form_code','=',$form_code)
					->orderBy('value_id', 'desc')
					->limit(5)
					->get();

			$json_values = $json_values->sortBy('value_id');
			$properties = FormPosition::where('form_code', '=', $form_code)
					->where('property_code','<>','header')
					->orderBy('property_position')
					->get();
			$form = Form::find($form_code);

			return view('form_values.index', [
					'json_values' => $json_values,
					'properties' => $properties,
					'form' => $form,
					'patient'=>$encounter->patient,
					'admission'=>$admission,
					'encounter_id'=>$encounter_id,
					'consultation'=>$consultation,
			]);
	}

	public function results(Request $request, $encounter_id)
	{
			$consultation = Consultation::find(Session::get('consultation_id'));
			$encounter = Encounter::find($encounter_id);
			$admission = EncounterHelper::getCurrentAdmission($encounter_id);

			$sql = sprintf("
				select a.form_code, form_name, result_count
				from forms a
				left join (select form_code, count(form_code) as result_count from form_values where encounter_id=%d group by form_code) b on (b.form_code = a.form_code)
				where result_count>0
				order by result_count desc, form_name
				", $encounter_id);

			$results = DB::select($sql);

			$form_codes = FormValue::distinct()
								->where('encounter_id','=', $encounter_id)
								->get(['form_code']);

			if (empty($request->search)) {
					$forms = DB::table('forms')
							->orderBy('form_name')
							->whereNotIn('form_code', $form_codes->pluck('form_code'))
							->paginate($this->paginateValue);
			} else {
					$forms = DB::table('forms')
							->where('form_name','like','%'.$request->search.'%')
							->orWhere('form_code', 'like','%'.$request->search.'%')
							->orderBy('form_name')
							->whereNotIn('form_code', $form_codes->pluck('form_code'))
							->paginate($this->paginateValue);
			}

			return view('form_values.result', [
					'admission'=>$admission,
					'patient'=>$encounter->patient,
					'forms'=>$forms,
					'results'=>$results,
					'formHelper'=>new FormHelper(),
					'encounter_id'=>$encounter_id,
					'search'=>$request->search,
					'consultation'=>$consultation,
			]);
	}

	public function delete($id)
	{
		$form_value = FormValue::findOrFail($id);
		return view('form_values.destroy', [
			'form_value'=>$form_value
			]);
	}

	public function destroy($id)
	{	
			$form_value = FormValue::find($id);
			FormValue::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/form/'.$form_value->form_code.'/'.$form_value->encounter_id);
	}
}

