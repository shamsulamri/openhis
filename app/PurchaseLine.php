<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;
use DB;
use Log;

class PurchaseLine extends Model
{
	//use SoftDeletes;
	//protected $dates = ['deleted_at'];

	protected $table = 'purchase_lines';
	protected $fillable = [
				'purchase_id',
				'product_code',
				'unit_code',
				'uom_rate',
				'line_quantity',
				'line_unit_price',
				'tax_code',
				'tax_rate',
				'line_subtotal_tax',
				'line_subtotal',
				'batch_number',
				'expiry_date',
				'reference_id',
				'deleted_at'];
	
    protected $guarded = ['line_id'];
    protected $primaryKey = 'line_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'purchase_id'=>'required',
				'expiry_date'=>'size:10|date_format:d/m/Y',
			];
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	public function setExpiryDateAttribute($value)
	{
		if (DojoUtility::validateDate($value)==true) {
			$this->attributes['expiry_date'] = DojoUtility::dateWriteFormat($value);
		}
	}

	public function getExpiryDateAttribute($value)
	{
		return DojoUtility::dateReadFormat($value);
	}

	public function product()
	{
		return $this->belongsTo('App\Product','product_code');
	}

	public function purchase()
	{
		return $this->belongsTo('App\Purchase','purchase_id');
	}

	public function tax()
	{
		return $this->belongsTo('App\TaxCode','tax_code');
	}

	public function uom()
	{
		return $this->belongsTo('App\UnitMeasure','unit_code');
	}

	public function balanceQuantity() 
	{
			$balance = null;
			$total = $this->line_quantity;
			if (!empty($this->reference_id)) {
					$balance = PurchaseLine::where('reference_id', $this->line_id)
							->sum('line_quantity');
					$total = $total - $balance;
			} 

			return $total;
	}
	
}
