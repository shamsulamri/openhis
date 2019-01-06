<?php

namespace App;
use Carbon\Carbon;
use DB;
use App\Purchase;
use Log;
use Auth;

class PurchaseHelper
{
	public function openPurchaseRequest()
	{
			$count = Purchase::where('document_code', 'purchase_request')
						->where('purchase_posted', 1)
						->whereNull('status_code')
						->count();

			return $count;	
	}

	public function openPurchases()
	{
			$count = Purchase::where('purchase_posted', 0)
						->where('author_id', '=', Auth::user()->author_id)
						->count();

			return $count;	
	}
}
