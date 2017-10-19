<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;
use App\Order;
use Log;
use App\AMQPHelper as Amqp;
use App\DiagnosticOrder;

class OrderInvestigation extends Model
{
	protected $table = 'order_investigations';
	protected $fillable = [
				'order_id',
				'investigation_date',
				'urgency_code',
				'investigation_recur',
				'investigation_duration',
				'period_code',
				'frequency_code'];
	

	public function validate($input, $method) {
			$rules = [
				'investigation_date'=>'required|size:10|date_format:d/m/Y',
			];
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
	public function setInvestigationDateAttribute($value)
	{
		if (DojoUtility::validateDate($value)==true) {
			$this->attributes['investigation_date'] = DojoUtility::dateWriteFormat($value);
		}
	}


	/**
	public function getInvestigationDateAttribute($value)
	{
		return DojoUtility::dateReadFormat($value);
	}
	**/

	public function order() {
		return $this->belongsTo('App\Order','order_id');
	}	

	public function period() {
		return $this->belongsTo('App\Period','period_code');
	}	

	public function frequency() {
		return $this->belongsTo('App\Frequency','frequency_code');
	}	

	public function save(array $options = array())
	{
			$changed = $this->isDirty() ? $this->getDirty() : false;

			parent::save();

			if ($changed) 
			{
					//Log::info("Order investigation updated");
			}	
	}
}
