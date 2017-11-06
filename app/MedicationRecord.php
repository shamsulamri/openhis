<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class MedicationRecord extends Model
{
	protected $table = 'medication_records';
	protected $fillable = [
				'order_id',
				'user_id',
				'medication_index',
				'medication_slot',
				'medication_datetime'];
	
    protected $guarded = ['medication_id'];
    protected $primaryKey = 'medication_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'order_id'=>'required',
				'medication_slot'=>'required',
				'medication_datetime'=>'required',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	public function user()
	{
			return $this->belongsTo('App\User', 'user_id','id');
	}
	
	public function order()
	{
			return $this->belongsTo('App\Order', 'order_id');
	}
}
