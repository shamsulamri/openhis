<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class PurchaseDocument extends Model
{
	use SoftDeletes;
	protected $dates = ['deleted_at'];

	protected $table = 'purchase_documents';
	protected $fillable = [
				'document_code',
				'document_prefix',
				'document_name'];
	
    protected $guarded = ['document_code'];
    protected $primaryKey = 'document_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'document_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['document_code'] = 'required|max:20|unique:purchase_documents';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}
