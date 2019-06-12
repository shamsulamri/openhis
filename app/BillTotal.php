<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class BillTotal extends Model
{
	protected $dates = ['deleted_at'];

	protected $table = 'bill_totals';
	protected $fillable = [
				'encounter_id',
				'bill_total',
				'bill_grand_total',
				'bill_total_payable',
				'deleted_at'];
	
    protected $guarded = ['encounter_id'];
    protected $primaryKey = 'encounter_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'bill_total'=>'required',
				'bill_grand_total'=>'required',
				'bill_total_payable'=>'required',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}