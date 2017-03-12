<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class FormValue extends Model
{
	protected $table = 'form_values';
	protected $fillable = [
				'encounter_id',
				'patient_id',
				'order_id',
				'form_code',
				'form_value'];
	
    protected $guarded = ['value_id'];
    protected $primaryKey = 'value_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'encounter_id'=>'required',
				'form_code'=>'required',
			];

			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}



	public function form()
	{
			return $this->belongsTo('App\Form','form_code');
	}
	
}
