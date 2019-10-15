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

	public function openPurchases($store_code = null)
	{
			$purchase_count = Purchase::where('purchase_posted', 0)
					->where('author_id', '=', Auth::user()->author_id);

			if ($store_code) {
			$purchase_count = $purchase_count->where('store_code', $store_code);
			}

			$purchase_count = $purchase_count->count();

			return $purchase_count;	
	}

	public function backOrder($document_code)
	{
			/** Get backorder documents **/
			$sql = sprintf("
			select distinct(purchase_id) from (
				select a.purchase_id, (line_quantity-IFNULL(physical_quantity,0)) as outstanding_quantity
				from purchase_lines as a
				left join (
					select line_id, sum(inv_physical_quantity) as physical_quantity
					from inventories
					where line_id is not null
					and inv_posted = 1
					group by line_id
				) as b on (b.line_id = a.line_id)
				left join purchases as c on (c.purchase_id = a.purchase_id)
				left join products as d on (d.product_code = a.product_code)
				left join suppliers as e on (e.supplier_code = c.supplier_code)
				left join ref_unit_measures as f on (f.unit_code = a.unit_code)
				where a.line_id is not null
				and purchase_posted = 1
				and document_code = '%s'
			", $document_code);

			if (Auth::user()->authorization->indent_request == 0) {
				$sql = $sql."and author_id=".Auth::user()->author_id." ";
			}

			$sql = $sql."
				having outstanding_quantity>0) as x";
			

			$data = DB::select($sql);

			$purchase_ids = [];
			foreach($data as $id) {
					array_push($purchase_ids, $id->purchase_id);
			}
			$purchases = Purchase::whereIn('purchase_id', $purchase_ids)
							->orderBy('purchase_id', 'desc');

			return $purchases;
	}

	public function balanceQuantity($reference_id) 
	{
			$balance = null;
			if (!empty($reference_id)) {
					$balance = PurchaseLine::where('reference_id', $reference_id)
							->leftJoin('purchases as b', 'b.purchase_id', '=', 'purchase_lines.purchase_id')
							->where('purchase_posted',1)
							->sum('line_quantity');
			} 

			return $balance;
	}
}
