<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class BlockType extends Model
{
	use SoftDeletes;
	protected $dates = ['deleted_at'];

	protected $table = 'ref_blocks';
	protected $fillable = [
				'block_code',
				'block_name',
				'deleted_at'];
	
    protected $guarded = ['block_code'];
    protected $primaryKey = 'block_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'block_name'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['block_code'] = 'required|max:20.0|unique:ref_blocks';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}
}
