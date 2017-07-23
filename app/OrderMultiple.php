<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class OrderMultiple extends Model
{
	protected $table = 'order_multiples';
	protected $fillable = [
				'order_id',
				'updated_by',
				'order_completed'];
	
    protected $guarded = ['multiple_id'];
    protected $primaryKey = 'multiple_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'order_id'=>'required',
				'order_completed'=>'required',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
	public function updatedBy()
	{
			return $this->belongsTo('App\User', 'updated_by','id');
	}
}
