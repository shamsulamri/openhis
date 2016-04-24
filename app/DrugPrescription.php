<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class DrugPrescription extends Model
{
	protected $table = 'drug_prescriptions';
	protected $fillable = [
				'drug_code',
				'drug_strength',
				'unit_code',
				'drug_dosage',
				'dosage_code',
				'route_code',
				'frequency_code',
				'drug_duration',
				'period_code',
				'drug_total_unit',
				'drug_prn',
				'drug_instruction',
				'drug_meal'];
	
    protected $guarded = ['prescription_id'];
    protected $primaryKey = 'prescription_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'drug_code'=>'required',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}
