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
				'drug_total_unit'];

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
	
	public function period() {
		return $this->belongsTo('App\Period','period_code');
	}	

	public function frequency() {
		return $this->belongsTo('App\DrugFrequency','frequency_code');
	}	

	public function unit() {
		return $this->belongsTo('App\UnitMeasure','unit_code');
	}	

	public function dosage() {
		return $this->belongsTo('App\DrugDosage','dosage_code');
	}	

	public function route() {
		return $this->belongsTo('App\DrugRoute','route_code');
	}	
}
