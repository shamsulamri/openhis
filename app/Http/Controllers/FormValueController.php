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

class FormValueController extends Controller
{

	public function create($form_code, $patient_id)
	{
			
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
			$form_value->save();

			Session::flash('message', 'Record successfully created.');
			return redirect('/form/'.$form_code.'/'.$encounter_id);
	}

	public function edit($id) 
	{
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
			]);

	}

	public function show($form_code, $encounter_id)
	{
			$encounter = Encounter::find($encounter_id);
			$json_values = FormValue::where('encounter_id', '=', $encounter_id)
					->where('form_code','=',$form_code)
					->orderBy('value_id', 'desc')
					->limit(5)
					->get();

			$json_values = $json_values->sortBy('value_id');
			$properties = FormPosition::where('form_code', '=', $form_code)
					->orderBy('property_position')
					->get();
			$form = Form::find($form_code);

			return view('form_values.index', [
					'json_values' => $json_values,
					'properties' => $properties,
					'form' => $form,
					'patient'=>$encounter->patient,
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

