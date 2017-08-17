<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class Drug extends Model
{
	protected $table = 'drugs';
	protected $fillable = [
				'drug_generic_name',
				'drug_label',
				'active_ingredient',
				'drug_category',
				'drug_schedule',
				'drug_formulary',
				'drug_type',
				'item_subgroup',
				'trade_name',
				'strength',
				'uom_strength',
				'dosage_form',
				'sku_uom',
				'special_code',
				'instruction_code',
				'caution_code'];
	
    protected $guarded = ['drug_code'];
    protected $primaryKey = 'drug_code';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [

			];

			
        	if ($method=='') {
        	    $rules['drug_code'] = 'required|max:20.0|unique:drugs';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	public function instruction()
	{
			return $this->belongsTo('App\DrugInstruction', 'instruction_code');
	}
	
	public function special()
	{
			return $this->belongsTo('App\DrugSpecialInstruction', 'special_code');
	}

	public function caution()
	{
			return $this->belongsTo('App\DrugCaution', 'caution_code');
	}
}
