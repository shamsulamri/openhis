<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class Postcode extends Model
{
	protected $table = 'ref_postcodes';
	protected $fillable = [
				'STATE',
				'DISTRICT',
				'city_code',
				'postcode'];
	
    protected $guarded = ['postcode'];
    protected $primaryKey = 'postcode';
    public $incrementing = false;
    

	public function validate($input, $method) {
			$rules = [

			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
}
