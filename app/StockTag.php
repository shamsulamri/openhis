<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class StockTag extends Model
{
	use SoftDeletes;
	protected $dates = ['deleted_at'];

	protected $table = 'stock_tags';
	protected $fillable = [
				'move_code',
				'tag_name'];
	
    protected $guarded = ['tag_code'];
    protected $primaryKey = 'tag_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'tag_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['tag_code'] = 'required|max:20|unique:stock_tags';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}
