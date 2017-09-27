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
				'drug_dosage',
				'dosage_code',
				'frequency_code',
				'route_code',
				'drug_duration',
				'period_code',
				'drug_total_unit',
		];
	
    protected $guarded = ['prescription_id'];
    protected $primaryKey = 'prescription_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [

			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	public function product()
	{
			return $this->belongsTo('App\Product', 'drug_code', 'product_code');
	}
	
	public function frequency()
	{
			return $this->belongsTo('App\DrugFrequency', 'frequency_code');
	}
}
