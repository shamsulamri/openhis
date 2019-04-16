<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class Set extends Model
{
	protected $table = 'ref_sets';
	protected $fillable = [
				'set_code',
				'set_shortcut',
				'user_id',
				'set_name'];
	
    protected $guarded = ['set_code'];
    protected $primaryKey = 'set_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'set_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['set_code'] = 'required|max:20|unique:ref_sets';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	public function owner()
	{
			return $this->belongsTo('App\User', 'user_id','id');
	}

	public function products()
	{
			$products = OrderSet::where('set_code', $this->set_code)
							->select('b.product_code', 'product_name')
							->leftJoin('products as b', 'b.product_code', '=', 'order_sets.product_code')
							->orderBy('product_name')
							->get();

			return $products;
	}
	
}
