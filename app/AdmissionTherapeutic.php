<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class AdmissionTherapeutic extends Model
{
	protected $table = 'admission_therapeutics';
	protected $fillable = [
				'admission_id',
				'therapeutic_code',
				'therapeutic_value'];
	
    protected $guarded = ['admission_id'];
    protected $primaryKey = 'admission_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'therapeutic_code'=>'required',
				'therapeutic_value'=>'required',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}