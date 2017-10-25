<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Log;
use DB;
use Session;
use Gate;
use App\FormValue;
use App\Patient;
use App\DojoUtility;
use App\Form;
use App\FormProperty;
use App\Encounter;
use App\Consultation;
use App\Graph;

class ChartController extends Controller
{
	public function __construct()
	{
			$this->middleware('auth');
	}

	public function line($form_code, $encounter_id, $consultation_id = null)
	{
			$encounter = Encounter::find($encounter_id);

			$values = FormValue::where('form_code','=',$form_code)
						->where('encounter_id','=', $encounter_id)
						->orderBy('created_at')
						->get();

			$form = Form::find($form_code);

			$x = 0;
			$charts=[];

			$keys = json_decode($values[0]->form_value,true);

			for($i=0;$i<count($keys);$i++) {
					$key = array_keys($keys)[$i];
					$property = FormProperty::find($key);
					$chart_data=null;
					if ($property->property_type=='number') {
							foreach ($values as $key_values) {

									$key_value = json_decode($key_values->form_value,true);

									$data_point=null;
									$data_point["label"]=DojoUtility::dateLongFormat($key_values['created_at']);

									$data_point["value"]=$key_value[$key];
									if (!empty($data_point["value"])) {
											$chart_data[$x]=$data_point;
											Log::info($key);
											Log::info($data_point['value']);

											$x++;
									}
							}
					}

					if (!empty($chart_data)) {
						$charts[$i] = $chart_data;
					}
			}

			$keys =  array_keys($keys);
			$i=0;
			foreach($keys as $key) {
				$property = FormProperty::find($key);
				$keys[$i] = $property->property_name;
				$i++;
			}
			
			$rgbs[0] = "rgba(26,179,148,0.8)";
			$rgbs[1] = "rgba(255,176,0,0.8)";
			$rgbs[2] = "rgba(26,179,148,0.8)";
			$rgbs[3] = "rgba(255,176,0,0.8)";
				
			$consultation = null;
			if (Session::get('consultation_id')) {
				$consultation = Consultation::find(Session::get('consultation_id'));
			}
			
			return view('charts.test', [
					'chart_id'=>'test',
					'chart_title'=>$form->form_name,
					'charts'=>$charts,
					'rgbs'=>$rgbs,
					'keys'=>$keys,
					'patient'=>$encounter->patient,
					'encounter_id'=>$encounter_id,
					'form'=>$form,
					'consultation'=>$consultation,
			]);
	}

	/**
	public function partograph($encounter_id)
	{
			$encounter = Encounter::find($encounter_id);

			$graph = new Graph();
			$graph->width=800;
			$graph->height=200;
			$graph->margin_left=50;
			$graph->margin_top=20;

			$Graph = new Graph();
			$Graph->width=800;
			$Graph->height=200;
			$Graph->margin_left=50;
			$Graph->margin_top=20;

			$graph_values = FormValue::select('form_value', 'created_at')	
						->where('form_code','=','partograph')
						->where('encounter_id', '=', $encounter_id)
						->orderBy('created_at')
						->get();

			$consultation = null;
			if (Session::get('consultation_id')) {
				$consultation = Consultation::find(Session::get('consultation_id'));
			}

			$form = Form::find('partograph');

			return view('charts.partograph', [
				'graph'=>$graph,
				'graph_values'=>$graph_values,
				'Graph'=>$Graph,
				'patient'=>$encounter->patient,
				'consultation'=>$consultation,
				'encounter_id'=>$encounter_id,
				'form'=>$form,
			]);
	}

	public function vitalSign($encounter_id)
	{
			$encounter = Encounter::find($encounter_id);

			$graph = new Graph();
			$graph->width=800;
			$graph->height=200;
			$graph->margin_left=50;
			$graph->margin_top=20;

			$Graph = new Graph();
			$Graph->width=800;
			$Graph->height=200;
			$Graph->margin_left=50;
			$Graph->margin_top=20;

			$form_code = 'vital_signs';
			$graph_values = FormValue::select('form_value', 'created_at')	
						->where('form_code','=',$form_code)
						->where('encounter_id', '=', $encounter_id)
						->orderBy('created_at')
						->get();

			$consultation = null;
			if (Session::get('consultation_id')) {
				$consultation = Consultation::find(Session::get('consultation_id'));
			}

			$form = Form::find($form_code);

			return view('charts.vital_sign', [
				'graph'=>$graph,
				'graph_values'=>$graph_values,
				'Graph'=>$Graph,
				'patient'=>$encounter->patient,
				'consultation'=>$consultation,
				'encounter_id'=>$encounter_id,
				'form'=>$form,
			]);
	}

	public function growthChart($encounter_id)
	{
			$encounter = Encounter::find($encounter_id);

			$graph = new Graph();
			$graph->width=800;
			$graph->height=200;
			$graph->margin_left=50;
			$graph->margin_top=20;

			$Graph = new Graph();
			$Graph->width=800;
			$Graph->height=200;
			$Graph->margin_left=50;
			$Graph->margin_top=20;

			$form_code = 'growth_chart';
			$graph_values = FormValue::select('form_value', 'created_at')	
						->where('form_code','=',$form_code)
						->where('encounter_id', '=', $encounter_id)
						->orderBy('created_at')
						->get();

			$consultation = null;
			if (Session::get('consultation_id')) {
				$consultation = Consultation::find(Session::get('consultation_id'));
			}

			$form = Form::find($form_code);

			return view('charts.growth_chart', [
				'graph'=>$graph,
				'graph_values'=>$graph_values,
				'Graph'=>$Graph,
				'patient'=>$encounter->patient,
				'consultation'=>$consultation,
				'encounter_id'=>$encounter_id,
				'form'=>$form,
			]);
	}
	**/
	public function graph($form_code, $encounter_id)
	{
			$encounter = Encounter::find($encounter_id);

			$graph = new Graph();
			$graph->width=800;
			$graph->height=200;
			$graph->margin_left=50;
			$graph->margin_top=20;

			$Graph = new Graph();
			$Graph->width=800;
			$Graph->height=200;
			$Graph->margin_left=50;
			$Graph->margin_top=20;

			$form = Form::find($form_code);
			$blade = 'charts.'.$form_code;
			/*
			$graph_values = FormValue::select('form_value', 'created_at')	
						->where('form_code','=',$form_code)
						->where('encounter_id', '=', $encounter_id)
						->orderBy('created_at', 'desc')
						->get();
			 */

			$n_results = 48;
			if (!empty($form->form_results)) $n_results = $form->form_results;

			$sql = "select * from (
							select value_id, form_value, created_at from form_values
							where form_code = '%s'
							and encounter_id = %d
							order by value_id desc 
							limit %d
					) as x
					order by value_id
			";

			$sql = sprintf($sql, $form_code, $encounter_id, $n_results);

			$graph_values = DB::select($sql);


			$consultation = null;
			if (Session::get('consultation_id')) {
				$consultation = Consultation::find(Session::get('consultation_id'));
			}


			return view($blade, [
				'graph'=>$graph,
				'graph_values'=>$graph_values,
				'Graph'=>$Graph,
				'patient'=>$encounter->patient,
				'consultation'=>$consultation,
				'encounter_id'=>$encounter_id,
				'form'=>$form,
			]);
	}
}
