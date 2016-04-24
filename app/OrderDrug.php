<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class OrderDrug extends Model
{
	protected $table = 'order_drugs';
	protected $fillable = [
				'order_id',
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
				'drug_meal'];

	public function validate($input, $method) {
			$rules = [
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	public function order() {
		return $this->belongsTo('App\Order','order_id');
	}	
	
}
