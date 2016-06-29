<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class Bed extends Model
{
	protected $table = 'beds';
	protected $fillable = [
				'bed_code',
				'encounter_code',
				'class_code',
				'ward_code',
				'room_code',
				'status_code',
				'bed_name',
				'bed_virtual',
				'gender_code',
				'department_code'];
	
    protected $guarded = ['bed_code'];
    protected $primaryKey = 'bed_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'ward_code'=>'required',
				'bed_name'=>'required',
				'encounter_code'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['bed_code'] = 'required|max:20|unique:beds';
        	    $rules['encounter_code'] = 'required';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	public function ward()
	{
			return $this->belongsTo('App\Ward','ward_code', 'ward_code');
	}

	public function room()
	{
			return $this->belongsTo('App\Room', 'room_code', 'room_code');
	}

	public function wardClass()
	{
			return $this->belongsTo('App\WardClass', 'class_code', 'class_code');
	}

	public function classAvailable($class_code) 
	{
			$beds = DB::table('beds as a')
					->select(['b.admission_id','a.bed_code','bed_name','patient_name','ward_code', 'a.class_code','c.patient_id'])
					->leftJoin('admissions as b', 'b.bed_code', '=', 'a.bed_code')
					->leftJoin('encounters as c', 'c.encounter_id', '=', 'b.encounter_id')
					->leftJoin('patients as d', 'd.patient_id', '=', 'c.patient_id')
					->leftJoin('discharges as e', 'e.encounter_id', '=', 'c.encounter_id')
					->whereNull('discharge_id')
					->whereNull('patient_name')
					->where('class_code','=',$class_code);

			return $beds->count();
	}
}
