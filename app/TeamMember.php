<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class TeamMember extends Model
{
	protected $table = 'team_members';
	protected $fillable = [
				'team_code',
				'username'];
	
    protected $guarded = ['member_id'];
    protected $primaryKey = 'member_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'team_code'=>'required',
				'username'=>'required',
			];

			
			
			$messages = [
				'required' => 'This field is required'
			];
			
			return validator::make($input, $rules ,$messages);
	}

 	public function user()
	{
			return $this->hasOne('App\User','username','username');
	}
	
}
