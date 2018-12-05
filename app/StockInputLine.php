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
				'po_line_id',
				'product_code',
				'line_value',
				'line_snapshot_quantity',
				'line_quantity',
				'line_difference'];
	
    protected $guarded = ['line_id'];
    protected $primaryKey = 'line_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'line_quantity'=>'required',
				'line_value'=>'required',
			];
			
			//'amount_current'=>'required',
			
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

        	if ($method=='PUT') {
				$product = Product::find($this->attributes['product_code']);


				if ($stock_input->move_code=='transfer') {
        	   			$rules['line_quantity'] = 'lower_than_or_equal:'.$this->attributes['line_quantity'].','.$stock_store->stock_quantity;
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
			return 0;
		}

	}

	public function poline()
	{
		return $this->belongsTo('App\PurchaseOrderLine', 'po_line_id', 'line_id');
	}

	public function product()
	{
		return $this->belongsTo('App\Product', 'product_code');
	}

	public function batches()
	{
		return $this->hasMany('App\StockInputBatch', 'line_id');
	}

	public function stockInput()
	{
		return $this->belongsTo('App\StockInput', 'input_id');
	}

	public function invoice()
	{
		return $this->belongsTo('App\StockReceive', 'input_id');
	}
}
