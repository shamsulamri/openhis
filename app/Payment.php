<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class Payment extends Model
{
	protected $table = 'payments';
	protected $fillable = [
				'encounter_id',
				'payment_amount',
				'payment_code',
				'user_id',
				'payment_description'];
	
    protected $guarded = ['payment_id'];
    protected $primaryKey = 'payment_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'encounter_id'=>'required',
				'payment_amount'=>'required',
				'payment_code'=>'required',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	public function encounter()
	{
			return $this->belongsTo('App\Encounter','encounter_id');
	}
}
