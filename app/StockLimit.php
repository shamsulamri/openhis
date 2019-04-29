<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class StockLimit extends Model
{
	protected $table = 'stock_limits';
	protected $fillable = [
				'product_code',
				'store_code',
				'limit_max',
				'reorder_quantity',
				'limit_min'];
	
    protected $guarded = ['limit_id'];
    protected $primaryKey = 'limit_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'product_code'=>'required',
				'store_code'=>'required',
				'limit_reorder'=>'required',
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
}
