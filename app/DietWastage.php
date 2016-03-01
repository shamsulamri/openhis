<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class DietWastage extends Model
{
	protected $table = 'diet_wastages';
	protected $fillable = [
				'waste_date',
				'ward_code',
				'period_code',
				'waste_unit',
				'waste_note'];
	
    protected $guarded = ['waste_id'];
    protected $primaryKey = 'waste_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'waste_date'=>'size:10|date_format:d/m/Y',
				'ward_code'=>'required',
				'waste_unit'=>'required',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
	public function setWasteDateAttribute($value)
	{
		if (DojoUtility::validateDate($value)==true) {
			$this->attributes['waste_date'] = DojoUtility::dateWriteFormat($value);
		}
	}


	public function getWasteDateAttribute($value)
	{
		return DojoUtility::dateReadFormat($value);
	}

}