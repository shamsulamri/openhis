<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class ConsultationAnnotation extends Model
{
	protected $table = 'consultation_annotations';
	protected $fillable = [
				'consultation_id',
				'annotation_image',
				'annotation_dataurl'];
	
    protected $guarded = ['annotation_id'];
    protected $primaryKey = 'annotation_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'consultation_id'=>'required',
				'annotation_image'=>'required',
				'annotation_dataurl'=>'required',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}
