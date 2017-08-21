<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;
use Log;

class StockInputLine extends Model
{
	protected $table = 'stock_input_lines';
	protected $fillable = [
				'input_id',
				'product_code',
				'batch_number',
				'amount_current',
				'amount_new',
				'amount_difference'];
	
    protected $guarded = ['line_id'];
    protected $primaryKey = 'line_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'amount_new'=>'required',
				'amount_current'=>'required',
			];

			
			
			$messages = [
				'required' => 'This field is required',
				'amount_current.required'=>'Insufficient amount'
			];
			
			Log::info($method);

			$stock_input = StockInput::find($this->attributes['input_id']);
			$stock_store = StockStore::where('store_code',$stock_input->store_code)
							->where('product_code', $this->attributes['product_code'])
							->first();

			if ($stock_store) {
						$this->attributes['amount_current'] = $stock_store->stock_quantity;
			}
			Log::info("----------------------------");
			Log::info($this->attributes['amount_current']);

        	if ($method=='PUT') {
				$product = Product::find($this->attributes['product_code']);


				if ($stock_input->move_code=='transfer') {
        	   			$rules['amount_new'] = 'lower_than_or_equal:'.$this->attributes['amount_new'].','.$stock_store->stock_quantity;
						$messages['lower_than_or_equal']="Quantity cannot be greater than on-hand.";
				}

				if ($product->product_track_batch) {
						$rules['batch_number'] = 'required';
				}
        	}
			return validator::make($input, $rules ,$messages);
	}

	
	public function getOnHand() 
	{
		if (!empty($this->amount_current)) {
			return $this->amount_current;
		} else {
			return "-";
		}

	}
	public function product()
	{
		return $this->belongsTo('App\Product', 'product_code');
	}
}
