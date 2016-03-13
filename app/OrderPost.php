<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class OrderPost extends Model
{
	protected $table = 'order_posts';
	protected $fillable = [
				'consultation_id',
	];
	
    protected $guarded = ['post_id'];
    protected $primaryKey = 'post_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'consultation_id'=>'required',
			];
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}
