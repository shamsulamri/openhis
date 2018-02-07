<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class OrderStop extends Model
{
	protected $table = 'order_stops';
	protected $fillable = [
				'order_id',
				'user_id'];
	
    protected $guarded = ['stop_id'];
    protected $primaryKey = 'stop_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'order_id'=>'required',
				'user_id'=>'required',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
	public function order()
	{
			return $this->belongsTo('App\Order','order_id');
	}
}
