<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class Purchase extends Model
{
	protected $table = 'purchases';
	protected $fillable = [
				'purchase_number',
				'document_code',
				'author_id',
				'username',
				'supplier_code',
				'purchase_description',
				'purchase_reference',
				'store_code',
				'purchase_posted',
				'status_code',
				'deleted_at'];
	
    protected $guarded = ['purchase_id'];
    protected $primaryKey = 'purchase_id';
    public $incrementing = true;
    

	public function validate($input, $method) {

			$rules = [
				'document_code'=>'required',
				'supplier_code'=>'required_unless:document_code,==,purchase_request',
				'purchase_date'=>'size:10|date_format:d/m/Y',
				'store_code'=>'required_if:document_code,==,goods_receive|required_if:document_code,==,purchase_invoice',
			];
			
			$messages = [
				'required' => 'This field is required',
				'required_if' => 'This field is required when document is a goods receive or purchase invoice',
				//'store_code.required_if'=>'guano demo!'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	public function supplier()
	{
		return $this->belongsTo('App\Supplier', 'supplier_code');
	}

	public function store()
	{
		return $this->belongsTo('App\Store', 'store_code');
	}

	public function document()
	{
		return $this->belongsTo('App\PurchaseDocument', 'document_code');
	}

	public function purchaseRequestStatus()
	{
		return $this->belongsTo('App\PurchaseRequestStatus', 'status_code');
	}

	public function user()
	{
		return $this->belongsTo('App\User', 'username', 'username');
	}

}
