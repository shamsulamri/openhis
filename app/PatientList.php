<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class PatientList extends Model
{
	protected $table = 'queues';
	protected $fillable = [
				'encounter_id',
				'location_code'];
	
    protected $guarded = ['queue_id'];
    protected $primaryKey = 'queue_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'encounter_id'=>'required',
				'location_code'=>'required',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}