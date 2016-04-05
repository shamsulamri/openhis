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
				'bed_code',
				'book_date',
				'book_description'];
	
    protected $guarded = ['book_id'];
    protected $primaryKey = 'book_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'patient_id'=>'required',
				'book_date'=>'size:10|date_format:d/m/Y',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
	public function setBookDateAttribute($value)
	{
		if (DojoUtility::validateDate($value)==true) {
			$this->attributes['book_date'] = DojoUtility::dateWriteFormat($value);
		}
	}


	public function getBookDateAttribute($value)
	{
		return DojoUtility::dateReadFormat($value);
	}

	public function bed()
	{
			return $this->belongsTo('App\Bed', 'bed_code');
	}
}
