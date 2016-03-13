<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;
use Log;

class OrderCancellation extends Model
{
	protected $table = 'order_cancellations';
	protected $fillable = [
				'order_id',
				'user_id',
				'cancel_reason'];
	
    protected $guarded = ['cancel_id'];
    protected $primaryKey = 'cancel_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			
			Log::info($input);		
			$rules = [
				'order_id'=>'required',
				'cancel_reason'=>'required',
			];
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	public function order()
	{
			return $this->belongsTo('App\Order');
	}
	
}
