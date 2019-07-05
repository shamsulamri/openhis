<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class ConsultationHistory extends Model
{
	use SoftDeletes;
	protected $dates = ['deleted_at'];

	protected $table = 'consultation_histories';
	protected $fillable = [
				'patient_id',
				'history_code',
				'history_note'];
	
    protected $guarded = ['history_id'];
    protected $primaryKey = 'history_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'patient_id'=>'required',
				'history_code'=>'required',
				'history_note'=>'required',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}
