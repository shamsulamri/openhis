<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class DietMenu extends Model
{
	protected $table = 'diet_menus';
	protected $fillable = [
				'menu_id',
				'diet_code',
				'product_code',
				'class_code',
				'period_code',
				'week_index',
				'day_index'];
	
    protected $guarded = ['menu_id'];
    protected $primaryKey = 'menu_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'diet_code'=>'required',
				'product_code'=>'required',
				'class_code'=>'required',
				'period_code'=>'required',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	public function product()
	{
			return $this->belongsTo('App\Product', 'product_code');
	}
	
	public function dietClass()
	{
			return $this->belongsTo('App\DietClass', 'class_code');
	}

	public function dietPeriod()
	{
			return $this->belongsTo('App\DietPeriod', 'period_code');
	}
}
