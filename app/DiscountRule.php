<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class DiscountRule extends Model
{
	use SoftDeletes;
	protected $dates = ['deleted_at'];

	protected $table = 'discount_rules';
	protected $fillable = [
				'sponsor_code',
				'product_code',
				'category_code',
				'parent_code',
				'discount_amount'];
	
    protected $guarded = ['rule_id'];
    protected $primaryKey = 'rule_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'discount_amount'=>'required',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
	public function sponsor()
	{
			return $this->belongsTo('App\Sponsor', 'sponsor_code');
	}
}
