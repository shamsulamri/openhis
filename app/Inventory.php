<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;
use App\InventoryBatch;
use App\StockHelper;
use Log;

class Inventory extends Model
{
	//use SoftDeletes;
	//protected $dates = ['deleted_at'];

	protected $table = 'inventories';
	protected $fillable = [
				'line_id',
				'order_id',
				'move_id',
				'move_reference',
				'move_description',
				'move_code',
				'store_code',
				'product_code',
				'unit_code',
				'uom_rate',
				'inv_datetime',
				'inv_book_quantity',
				'inv_physical_quantity',
				'inv_quantity',
				'inv_unit_cost',
				'inv_subtotal',
				'inv_description',
				'inv_batch_number',
				'loan_id',
				'username'];
	
    protected $guarded = ['inv_id'];
    protected $primaryKey = 'inv_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'product_code'=>'required',
				'inv_quantity'=>'required',
				'inv_subtotal'=>'required',
				'inv_physical_quantity'=>'less_than_or_equal:inv_book_quantity',
				//'store_code'=>'required_if:document_code,==,goods_receive|required_if:document_code,==,purchase_invoice',
			];


        	if ($method=='') {
				$rules['inv_physical_quantity'] = 'less_than_or_equal:inv_book_quantity';
        	    $rules['move_code'] = 'required';
			} 
			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}
	
	public function setInvExpiryDateAttribute($value)
	{
		if (DojoUtility::validateDate($value)==true) {
			$this->attributes['inv_expiry_date'] = DojoUtility::dateWriteFormat($value);
		}
	}

	public function getInvExpiryDateAttribute($value)
	{
		return DojoUtility::dateReadFormat($value);
	}

	public function movement()
	{
		return $this->belongsTo('App\StockMovement', 'move_code');
	}

	public function store()
	{
		return $this->belongsTo('App\Store', 'store_code');
	}

	public function product()
	{
		return $this->belongsTo('App\Product', 'product_code');
	}

	public function unit()
	{
		return $this->belongsTo('App\UnitMeasure', 'unit_code');
	}
	
	public function batch()
	{
			$batch = InventoryBatch::where('product_code', $this->product_code)
						->where('batch_number', '=', $this->inv_batch_number)
						->first();

			return $batch;
	}

	public static function boot()
	{
			parent::boot();

			static::created(function($inventory)
			{
				$helper = new StockHelper();
				$helper->updateStockOnHand($inventory->product_code);
			});


	}
}
