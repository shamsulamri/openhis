<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class DischargeSummary extends Model
{
	protected $dates = ['deleted_at'];

	protected $table = 'discharge_summaries';
	protected $fillable = [
				'encounter_id',
				'summary_diagnosis',
				'summary_treatment',
				'summary_surgical',
				'summary_follow_up',
				'summary_medication'];
	
    protected $guarded = ['encounter_id'];
    protected $primaryKey = 'encounter_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [

			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	public function encounter() 
	{
			return $this->belongsto('App\Encounter', 'encounter_id');
	}
	
}
