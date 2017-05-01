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

class ChartController extends Controller
{
	public function __construct()
	{
			$this->middleware('auth');
	}

	public function line($form_code, $encounter_id)
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

			return view('charts.test', [
					'chart_id'=>'test',
					'chart_title'=>$form->form_name,
					'charts'=>$charts,
					'rgbs'=>$rgbs,
					'keys'=>$keys,
					'patient'=>$encounter->patient,
					'encounter_id'=>$encounter_id,
					'form'=>$form,
			]);
	}

}
