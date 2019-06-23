<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class PatientMrn extends Model
{
	use SoftDeletes;
	protected $dates = ['deleted_at'];

	protected $table = 'patient_mrns';
	protected $fillable = [
				'patient_id',
				'mrn_old'];
	
    protected $guarded = ['mrn_id'];
    protected $primaryKey = 'mrn_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'patient_id'=>'required',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}
