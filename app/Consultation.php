<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;
use App\Patient;

class Consultation extends Model
{
	protected $table = 'consultations';
	protected $fillable = [
				'encounter_id',
				'user_id',
				'consultation_status',
				'consultation_notes',
				];
	
    protected $guarded = ['consultation_id'];
    protected $primaryKey = 'consultation_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'encounter_id'=>'required',
				'user_id'=>'required',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	public function encounter()
	{
			return $this->belongsTo('App\Encounter');
	}

 	public function user()
	{
			return $this->hasOne('App\User','id','user_id');
	}


}
