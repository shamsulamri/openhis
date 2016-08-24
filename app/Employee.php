<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class Employee extends Model
{
	protected $connection = 'mysql2';
	protected $table = 'siso_emp_his';
	protected $fillable = [
				'id',
				'title',
				'nickname',
				'app_date',
				'resign_date',
				'conf_date',
				'mobile',
				'office',
				'house',
				'code_campus',
				'emp_type',
				'emp_category',
				'empid',
				'dept_id',
				'name',
				'ic_passport',
				'passport',
				'passport_sdate',
				'passport_edate',
				'visa_no',
				'visa_sdate',
				'visa_edate',
				'expected_date',
				'teach_no',
				'teach_sdate',
				'teach_edate',
				'emp_medical_status',
				'emp_medical_stat_date',
				'kwsp_no',
				'tax_no',
				'employer_no',
				'socso_no',
				'zakat_no',
				'bumi_status',
				'ptptn_no',
				'tabung_haji',
				'unit_id',
				'sub_unit_id',
				'old_ic',
				'position',
				'dob',
				'age',
				'birthplace',
				'gender',
				'status',
				'xemployee_status',
				'staff_mode',
				'print_card_status',
				'collect_card_status',
				'salary_rev_date',
				'race',
				'religion',
				'email',
				'url',
				'address_aa',
				'address_ab',
				'city_a',
				'state_a',
				'postcode_a',
				'country_a',
				'citizenship',
				'address_ba',
				'address_bb',
				'city_b',
				'state_b',
				'postcode_b',
				'country_b',
				'pic_name',
				'mime_type',
				'mime_name',
				'card_id',
				'reason',
				'pic_contents',
				'new_inserted_date',
				'new_inserted_by',
				'last_modified_date',
				'last_modified_by',
				'data_mig_date'];
	
    protected $guarded = ['empid'];
    protected $primaryKey = 'empid';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [

			];

			
        	if ($method=='') {
        	    $rules['empid'] = 'required|max:20.0|unique:siso_emp_his';
        	}
        
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}
