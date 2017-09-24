<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class UserAuthorization extends Model
{
	protected $table = 'user_authorizations';
	protected $fillable = [
				'author_name',
				'module_patient',
				'module_consultation',
				'module_order',
				'module_inventory',
				'module_ward',
				'module_support',
				'module_discharge',
				'module_diet',
				'module_medical_record',
				'patient_list',
				'product_list',
				'product_information_edit',
				'product_purchase_edit',
				'product_sale_edit',
				'loan_function',
				'store_code',
				'location_code',
				'view_progress_note',
				'identification_prefix',
				'appointment_function',
				'system_administrator'];
	
    protected $guarded = ['author_id'];
    protected $primaryKey = 'author_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'author_name'=>'required',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}
