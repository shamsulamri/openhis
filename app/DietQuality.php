<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class DietQuality extends Model
{
	protected $table = 'diet_qualities';
	protected $fillable = [
				'qc_date',
				'period_code',
				'class_code',
				'qc_texture',
				'qc_texture_note',
				'qc_taste',
				'qc_taste_note',
				'qc_aroma',
				'qc_aroma_note',
				'qc_color',
				'qc_color_note',
				'qc_size',
				'qc_size_note',
				'qc_comments',
				'qc_suggestion'];
	
    protected $guarded = ['qc_id'];
    protected $primaryKey = 'qc_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'qc_date'=>'size:10|date_format:d/m/Y|required',
				'period_code'=>'required',
				'class_code'=>'required',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
	public function setQcDateAttribute($value)
	{
		if (DojoUtility::validateDate($value)==true) {
			$this->attributes['qc_date'] = DojoUtility::dateWriteFormat($value);
		}
	}


	public function getQcDateAttribute($value)
	{
		return DojoUtility::dateReadFormat($value);
	}

}
