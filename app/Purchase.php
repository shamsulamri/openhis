<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class Purchase extends Model
{
	use SoftDeletes;
	protected $dates = ['deleted_at'];

	protected $table = 'purchases';
	protected $fillable = [
				'purchase_number',
				'document_code',
				'author_id',
				'supplier_code',
				'purchase_date',
				'purchase_description',
				'purchase_reference',
				'deleted_at'];
	
    protected $guarded = ['purchase_id'];
    protected $primaryKey = 'purchase_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'document_code'=>'required',
				'supplier_code'=>'required',
				'purchase_date'=>'size:10|date_format:d/m/Y',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
	public function setPurchaseDateAttribute($value)
	{
		if (DojoUtility::validateDate($value)==true) {
			$this->attributes['purchase_date'] = DojoUtility::dateWriteFormat($value);
		}
	}


	public function getPurchaseDateAttribute($value)
	{
		return DojoUtility::dateReadFormat($value);
	}

	public function supplier()
	{
		return $this->belongsTo('App\Supplier', 'supplier_code');
	}

	public function document()
	{
		return $this->belongsTo('App\PurchaseDocument', 'document_code');
	}

}
