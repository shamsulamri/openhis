<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class DrugDisease extends Model
{
	protected $table = 'drug_diseases';
	protected $fillable = [
				'drug_code',
				'indication_code'];
	
    protected $guarded = ['disease_id'];
    protected $primaryKey = 'disease_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [

			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
	public function indication()
	{
			return $this->belongsTo('App\DrugIndication', 'indication_code');
	}
}
