<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\OrderDrugLabel;
use Log;

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

    protected $guarded = ['id'];
    protected $primaryKey = 'id';

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

	public static function boot()
	{
			parent::boot();

			static::deleted(function($orderDrug)
			{
					Log::info("Delete.......");
			});

			static::updated(function($orderDrug)
			{
					Log::info("Updated!!!!");
					$drug_label = OrderDrugLabel::where('order_id', $orderDrug->order_id)->first();
					$drug_label->drug_strength = $orderDrug->drug_strength;
					$drug_label->unit_code = $orderDrug->unit_code;
					$drug_label->drug_dosage = $orderDrug->drug_dosage;
					$drug_label->dosage_code = $orderDrug->dosage_code;
					$drug_label->route_code = $orderDrug->route_code;
					$drug_label->frequency_code = $orderDrug->frequency_code;
					$drug_label->drug_duration = $orderDrug->drug_duration;
					$drug_label->period_code = $orderDrug->period_code;
					$drug_label->save();
			});

			/**
			static::created(function($orderDrug)
			{
					Log::info("Created!!!!");
					$drug_label = new OrderDrugLabel();
					$drug_label->order_id = $orderDrug->order_id;
					$drug_label->drug_strength = $orderDrug->drug_strength;
					$drug_label->unit_code = $orderDrug->unit_code;
					$drug_label->drug_dosage = $orderDrug->drug_dosage;
					$drug_label->dosage_code = $orderDrug->dosage_code;
					$drug_label->route_code = $orderDrug->route_code;
					$drug_label->frequency_code = $orderDrug->frequency_code;
					$drug_label->drug_duration = $orderDrug->drug_duration;
					$drug_label->period_code = $orderDrug->period_code;
					$drug_label->save();
					Log::info($drug_label);
			});
			**/
	}
}
