<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class StockInput extends Model
{
	protected $table = 'stock_inputs';
	protected $fillable = [
				'purchase_id',
				'move_code',
				'store_code_transfer',
				'input_description',
				'input_close',
				'store_code'];
	
    protected $guarded = ['input_id'];
    protected $primaryKey = 'input_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'move_code'=>'required',
				'store_code'=>'required',
				'store_code_transfer'=>'required_if:move_code,==,transfer',
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

	public function store_transfer()
	{
		return $this->belongsTo('App\Store', 'store_code_transfer', 'store_code');
	}
	public function movement()
	{
		return $this->belongsTo('App\StockMovement', 'move_code');
	}
	
	public function purchase()
	{
		return $this->belongsTo('App\PurchaseOrder', 'purchase_id');
	}
}
