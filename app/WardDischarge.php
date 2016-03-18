<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class WardDischarge extends Model
{
	protected $table = 'ward_discharges';
	protected $fillable = [
				'discharge_description'];
	
    protected $guarded = ['discharge_id'];
    protected $primaryKey = 'discharge_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [

			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}