<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class Refund extends Model
{
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $table = 'refunds';
	protected $fillable = [
				'patient_id',
				'user_id',
				'refund_reference',
				'refund_type',
				'refund_amount',
				'refund_description'];
	
    protected $guarded = ['refund_id'];
    protected $primaryKey = 'refund_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'refund_type'=>'required',
				'refund_amount'=>'required',
				'refund_reference'=>'required',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	public function patient()
	{
			return $this->belongsTo('App\Patient','patient_id');
	}
	
}
