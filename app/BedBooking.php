<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class BedBooking extends Model
{
	protected $table = 'bed_bookings';
	protected $fillable = [
				'patient_id',
				'admission_id',
				'ward_code',
				'class_code',
				'book_date',
				'book_description'];
	
    protected $guarded = ['book_id'];
    protected $primaryKey = 'book_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'patient_id'=>'required',
				'class_code'=>'required',
				'ward_code'=>'required',
				'book_date'=>'size:16|date_format:d/m/Y H:i',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
	public function setBookDateAttribute($value)
	{
		if (DojoUtility::validateDateTime($value)==true) {
			$this->attributes['book_date'] = DojoUtility::dateTimeWriteFormat($value);
		}
	}

	public function getBookDateAttribute($value)
	{
		return DojoUtility::dateTimeReadFormat($value);
	}


	public function bed()
	{
			return $this->belongsTo('App\Bed', 'bed_code');
	}

	public function patient()
	{
			return $this->belongsTo('App\Patient', 'patient_id');
	}
}
