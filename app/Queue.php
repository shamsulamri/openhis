<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class Queue extends Model
{
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $table = 'queues';
	protected $fillable = [
				'encounter_id',
				'location_code'];
	
    protected $guarded = ['queue_id'];
    protected $primaryKey = 'queue_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'encounter_id'=>'required',
				'location_code'=>'required',
			];

			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	public function location()
	{
			return $this->hasOne('App\QueueLocation', 'location_code', 'location_code');
	}
	
	public function encounter() 
	{
			return $this->belongsTo('App\Encounter', 'encounter_id', 'encounter_id');
	}
}
