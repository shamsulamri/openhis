<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class StockBatch extends Model
{
	use SoftDeletes;
	protected $dates = ['deleted_at'];

	protected $table = 'stock_batches';
	protected $fillable = [
				'stock_id',
				'store_code',
				'product_code',
				'batch_number',
				'expiry_date',
				'batch_quantity'];
	
    protected $guarded = ['batch_id'];
    protected $primaryKey = 'batch_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'stock_id'=>'required',
				'batch_number'=>'required',
				'batch_quantity'=>'required',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
	public function store()
	{
			return $this->belongsTo('App\Store', 'store_code');
	}

	public function product()
	{
		return $this->belongsTo('App\Product', 'product_code');
	}

	public function stock()
	{
		return $this->belongsTo('App\Stock', 'stock_id');
	}
}
