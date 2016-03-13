<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class TaskCancellation extends Model
{
	protected $table = 'order_cancellations';
	protected $fillable = [
				'order_id',
				'cancel_reason',
				'user_id'];
	
    protected $guarded = ['cancel_id'];
    protected $primaryKey = 'cancel_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'order_id'=>'required',
				'cancel_reason'=>'required',
				'user_id'=>'required',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}