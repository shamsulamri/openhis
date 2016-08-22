<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class Document extends Model
{
	protected $table = 'documents';
	protected $fillable = [
				'patient_mrn',
				'type_code',
				'document_description',
				'document_status',
				'document_uuid',
				'document_file',
				'document_location'];
	
    protected $guarded = ['document_id'];
    protected $primaryKey = 'document_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'patient_mrn'=>'required',
				'type_code'=>'required',
				'document_status'=>'required',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	public function document()
	{
			return $this->belongsTo('App\DocumentType', 'type_code');
	}
	
	public function patient()
	{
			return $this->belongsTo('App\Patient', 'patient_mrn', 'patient_mrn');
	}

	public function status()
	{
			return $this->belongsTo('App\DocumentStatus', 'document_status', 'status_code');
	}
}
