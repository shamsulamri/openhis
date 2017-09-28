<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class BillAging extends Model
{
	protected $table = 'bill_agings';
	protected $fillable = [
				'encounter_id',
				'sponsor_code',
				'age_amount',
				'age_days',
				'age_group'];
	
    protected $guarded = ['encounter_id'];
    protected $primaryKey = 'encounter_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'age_amount'=>'required',
				'age_days'=>'required',
				'age_group'=>'required',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}
