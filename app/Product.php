<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;
use Log;
use App\GeneralLedger;
use App\ProductUom;
use App\StockHelper;

class Product extends Model
{
	use SoftDeletes;
	protected $dates = ['deleted_at'];

	protected $table = 'products';
	protected $fillable = [
				'product_name',
				'product_name_other',
				'product_custom_id',
				'category_code',
				'order_form',
				'product_unit_charge',
				'product_stocked',
				'product_drop_charge',
				'product_reorder',
				'form_code',
				'unit_code',
				'charge_code',
				'status_code',
				'product_average_cost',
				'product_on_hand',
				'product_drop_charge',
				'product_local_store',
				'product_non_claimable',
				'product_duration_use',
				'product_input_tax',
				'product_output_tax'];
	
    	protected $guarded = ['product_code'];
    	protected $primaryKey = 'product_code';
    	public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [
				'product_name'=>'required',
				'category_code'=>'required',
				'order_form'=>'required',
			];

			
        	if ($method=='') {
        	    $rules['product_code'] = 'required|max:20.0|unique:products|alpha_dash';
        	}
        
			
			$messages = [
					'required' => 'This field is required',
					'product_sale_price.required_if' => 'Field required when product is sold to patient',
			];

        	if ($method=='PUT') {
				$product = Product::find($this->attributes['product_code']);
        	}
			
			return validator::make($input, $rules ,$messages);
	}

	public function setProductNameAttribute($value)
	{
			$this->attributes['product_name'] = strtoupper($value);
	}

	public function getProductNameAttribute($value)
	{
			return strtoupper($value);
	}

	public function bom()
	{
			return $this->hasMany('App\BillMaterial', 'product_code');
	}

	public function category()
	{
			return $this->belongsTo('App\ProductCategory','category_code');
	}

	public function feeSchedule()
	{
			return $this->hasOne('App\FeeSchedule', 'fee_code', 'product_code');
	}
	public function productUnitMeasures()
	{
			return $this->hasMany('App\ProductUom', 'product_code')->get();
	}

	public function orderForm()
	{
			return $this->belongsTo('App\OrderForm', 'order_form');
	}

	public function resultForm()
	{
			return $this->belongsTo('App\Form', 'form_code');
	}

	public function outputTax()
	{
			return $this->belongsTo('App\TaxCode', 'product_output_tax');
	}

	public function inputTax()
	{
			return $this->belongsTo('App\TaxCode', 'product_input_tax');
	}

	public function status()
	{
			return $this->belongsTo('App\ProductStatus', 'status_code');
	}

	public function drug()
	{
			return $this->hasOne('App\Drug', 'drug_code', 'product_code');
	}

	public function uom()
	{
			return $this->hasMany('App\ProductUom', 'product_code');
	}

	public function bed()
	{
			return $this->hasOne('App\Bed', 'bed_code', 'product_code');
	}

	public function getOrderFormName()
	{
			if ($this->attributes['order_form']) {
				return OrderForm::find($this->attributes['order_form'])->form_name;
			} else {
				return "";
			}

	}

	public function getFormName()
	{
			if ($this->attributes['form_code']) {
				return Form::find($this->attributes['form_code'])->form_name;
			} else {
				return "-";
			}
	}

	public function getProductOnHandAttribute($value) 
	{
			return floatval($value);
	}

	public function getTotalOnHand()
	{
			$helper = new StockHelper();

			return $helper->getStockOnHand($this->attributes['product_code']);
			//return floatval($value);
	}
	public function unit() 
	{
			//return $this->uom()->where('unit_code', 'unit')->first();
			return $this->belongsTo('App\UnitMeasure', 'unit_code');
	}

	public function uomDefaultCost()
	{
			$product_code = $this->attributes['product_code'];
			$uom = ProductUom::where('product_code', '=', $product_code)
					->where('uom_default_cost', '=', 1)
					->select('unit_code', 'uom_cost', 'uom_rate')
					->first();

			return $uom?:null;
	}

	public function uomByUnit($unit_code)
	{
			$product_code = $this->attributes['product_code'];
			$uom = ProductUom::where('product_code', '=', $product_code)
					->where('unit_code', '=', $unit_code)
					->first();

			Log::info('---> '.$product_code);
			return $uom?:null;
	}

	public function hasBatches()
	{
			$helper = new StockHelper();
			$batches = $helper->getBatches($this->attributes['product_code'])?:null;

			Log::info("Batche == ".$batches->count());
			if ($batches->count()>0) {
					return true;
			} else {
					return false;
			}
	}

	public function uomDefaultPrice($encounter = null)
	{
			$product_code = $this->attributes['product_code'];

			$category = ProductCategory::find($this->attributes['category_code']);

			$uom = ProductUom::select('unit_code', 'uom_price')
					->where('product_code', '=', $product_code)
					->where('uom_default_price', '=', 1)
					->first();

			if ($category->category_price == 'public_vs_sponsor') {
					if ($encounter->type_code=='sponsored') {
							$uom = ProductUom::select('unit_code', 'uom_price_2 as uom_price')
									->where('product_code', '=', $product_code)
									->where('uom_default_price', '=', 1)
									->first();
					}
			}

			if ($category->category_price == 'outpatient_vs_inpatient') {
					if ($encounter->encounter_code=='inpatient') {
							$uom = ProductUom::select('unit_code', 'uom_price_2 as uom_price')
									->where('product_code', '=', $product_code)
									->where('uom_default_price', '=', 1)
									->first();
					}
			}

			return $uom?:null;
	}

	public function getStockLimits($store_code)
	{
			$limits = StockLimit::where('product_code', $this->attributes['product_code'])
						->where('store_code', $store_code)
						->first();

			return $limits;
	}

	public static function boot()
	{
			parent::boot();

			static::deleted(function($product)
			{
				$product->uom()->delete();
			});

	}
}
