<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class Deposit extends Model
{
	protected $table = 'deposits';
	protected $fillable = [
				'encounter_id',
				'deposit_amount',
				'payment_code',
				'deposit_description',
				'user_id'];
	
    protected $guarded = ['deposit_id'];
    protected $primaryKey = 'deposit_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'encounter_id'=>'required',
				'deposit_amount'=>'required',
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
