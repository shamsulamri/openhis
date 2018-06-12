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
				'user_id',
				'priority_code',
				'book_description'];
	
    protected $guarded = ['book_id'];
    protected $primaryKey = 'book_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'patient_id'=>'required',
				'class_code'=>'required',
				'ward_code'=>'required',
				'user_id'=>'required',
				'book_date'=>'required|size:10|date_format:d/m/Y|after:now',
			];

			$messages = [
					'required' => 'This field is required',
					'book_date.after' => 'The book date must be greater than today.',
			];
			return validator::make($input, $rules ,$messages);
	}

	
	public function setBookDateAttribute($value)
	{
		if (DojoUtility::validateDate($value)==true) {
			$this->attributes['book_date'] = DojoUtility::dateWriteFormat($value);
		}
	}

	/**
	public function getBookDateAttribute($value)
	{
		return DojoUtility::dateTimeReadFormat($value);
	}
	**/


	public function patient()
	{
			return $this->belongsTo('App\Patient', 'patient_id');
	}

	public function ward()
	{
			return $this->belongsTo('App\Ward', 'ward_code');
	}
}
