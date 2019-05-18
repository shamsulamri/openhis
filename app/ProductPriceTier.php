<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class ProductPriceTier extends Model
{
	protected $table = 'product_price_tiers';
	protected $fillable = [
				'charge_code',
				'tier_min',
				'tier_max',
				'tier_outpatient',
				'tier_inpatient',
				'tier_outpatient_markup',
				'tier_inpatient_markup',
				'tier_outpatient_multiplier',
				'tier_inpatient_multiplier',
				'tier_outpatient_limit',
				'tier_inpatient_limit',
				'tier_public',
				'tier_sponsor',
				'tier_public_markup',
				'tier_sponsor_markup',
				'tier_public_multiplier',
				'tier_sponsor_multiplier',
				'tier_public_limit',
				'tier_sponsor_limit'];
	
    protected $guarded = ['tier_id'];
    protected $primaryKey = 'tier_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'charge_code'=>'required',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	public function charge()
	{
			return $this->belongsTo('App\ProductCharge', 'charge_code');
	}
	
}
