<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Validator;
use Carbon\Carbon;
use App\DojoUtility;

class InventoryMovement extends Model
{
	use SoftDeletes;
	protected $dates = ['deleted_at'];

	protected $table = 'inventory_movements';
	protected $fillable = [
				'purchase_id',
				'user_id',
				'move_code',
				'tag_code',
				'store_code',
				'target_store',
				'move_description',
				'move_number',
				'move_posted'];
	
    protected $guarded = ['move_id'];
    protected $primaryKey = 'move_id';
    public $incrementing = true;
    

	public function validate($input, $method) {
			$rules = [
				'move_code'=>'required',
				'store_code'=>'required',
				'tag_code'=>'required_if:move_code,==,stock_issue',
				'target_store'=>'required_if:tag_code,==,transfer',
			];

			
			//'store_code'=>'required_if:move_code,==,transfer',
			
			$messages = [
				'required' => 'This field is required',
			];
			
			return validator::make($input, $rules ,$messages);
	}

	
	public function store()
	{
		return $this->belongsTo('App\Store', 'store_code');
	}

	public function targetStore()
	{
		return $this->belongsTo('App\Store', 'target_store');
	}

	public function store_transfer()
	{
		return $this->belongsTo('App\Store', 'store_code_transfer', 'store_code');
	}

	public function movement()
	{
		return $this->belongsTo('App\StockMovement', 'move_code');
	}

	public function document()
	{
		return $this->belongsTo('App\PurchaseDocument', 'document_code','move_code');
	}

	public function tag()
	{
		return $this->belongsTo('App\StockTag', 'tag_code');
	}

	public function user()
	{
			return $this->belongsTo('App\User', 'user_id','id');
	}
}
