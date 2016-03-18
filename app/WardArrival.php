<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class WardArrival extends Model
{
	protected $table = 'ward_arrivals';
	protected $fillable = [
				'encounter_id',
				'arrival_description'];
	
    protected $guarded = ['arrival_id'];
    protected $primaryKey = 'arrival_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'encounter_id'=>'required',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}