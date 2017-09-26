<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class DietCensus extends Model
{
	protected $table = 'diet_censuses';
	protected $fillable = [
				'census_date',
				'diet_code',
				'census_count'];
	
    protected $guarded = ['census_id'];
    protected $primaryKey = 'census_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'census_date'=>'size:10|date_format:d/m/Y',
				'diet_code'=>'required',
				'census_count'=>'required',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
	public function setCensusDateAttribute($value)
	{
		if (DojoUtility::validateDate($value)==true) {
			$this->attributes['census_date'] = DojoUtility::dateWriteFormat($value);
		}
	}


	public function getCensusDateAttribute($value)
	{
		return DojoUtility::dateReadFormat($value);
	}

}
