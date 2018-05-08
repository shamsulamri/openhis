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
				'location_code',
				'ward_code'];
	
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

	public function category()
	{
			return $this->belongsTo('App\ProductCategory','category_code');
	}
	
	public function encounter()
	{
			return $this->belongsTo('App\EncounterType','encounter_code');
	}

	public function location()
	{
			return $this->belongsTo('App\QueueLocation','location_code');
	}
}
