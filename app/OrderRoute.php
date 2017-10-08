<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class OrderRoute extends Model
{
	protected $table = 'order_routes';
	protected $fillable = [
				'encounter_code',
				'category_code',
				'store_code'];
	
    protected $guarded = ['route_id'];
    protected $primaryKey = 'route_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'encounter_code'=>'required',
				'category_code'=>'required',
				'store_code'=>'required',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}