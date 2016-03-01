<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class StockMovement extends Model
{
	protected $table = 'stock_movements';
	protected $fillable = [
				'move_code',
				'move_name'];
	
    protected $guarded = ['move_code'];
    protected $primaryKey = 'move_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'move_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['move_code'] = 'required|max:20|unique:stock_movements';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}