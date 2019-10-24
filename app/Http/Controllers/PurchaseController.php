<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Input;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Purchase;
use Log;
use DB;
use Session;
use App\Supplier;
use Auth;
use App\DojoUtility;
use Carbon\Carbon;
use App\PurchaseLine;
use App\PurchaseDocument;
use App\Store;
use App\InventoryMovement;
use App\RnPurchaseRequest;
use App\PurchaseHelper;
use App\Ward;
use App\StockHelper;

class PurchaseController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index(Request $request)
	{
			$purchases = Purchase::orderBy('purchase_posted')
					->where(function ($query) use ($request) {
							$query->where('author_id', '=', Auth::user()->author_id)
								  ->orWhere('status_code', '=', 'approved');
					});

			//$store_code = StockHelper::getDefaultStore($request);
			$store_code = Auth::User()->defaultStore($request);
			if (!empty($store_code)) {
					$purchases = $purchases->where('store_code', $store_code);
			}

			$purchases = $purchases->orderBy('purchase_id', 'desc');

			$purchases = $purchases->paginate($this->paginateValue);

			return view('purchases.index', [
					'purchases'=>$purchases,
					'documents' => PurchaseDocument::all()->sortBy('document_name')->lists('document_name', 'document_code')->prepend('',''),
					'document_code' => null,
					'author_id'=>Auth::user()->author_id,
					'purchase_helper' => new PurchaseHelper(),
					'search'=>null,
					'status_code'=>null,
					'store_code'=>$store_code,
			]);
	}

	public function masterDocument(Request $request, $id = null)
	{
			$purchase = null;
			$movement = null;
			$reason = $request->reason?:null;
			$find = "Find previous document";

			$purchases = Purchase::orderBy('purchase_id', 'desc')
					->where('purchase_id', '<>', $id)
					->where('purchase_posted','=', 1)
					->where(function ($query) use ($request) {
							$query->where('author_id', '=', Auth::user()->author_id)
									->orWhere('author_id','=', 7);
					});
			//		->where('author_id', '=', Auth::user()->author_id);


			$purchases = $purchases
					->where(function ($query) use ($request) {
							$query->whereNull('status_code')
									->orWhere('status_code','!=', 'declined');
					});


			if ($reason == 'purchase') {
					$purchase = Purchase::find($request->purchase_id);
					$purchases = $purchases->where('supplier_code', '=', $purchase->supplier_code);
					$id = $request->purchase_id;
			}


			/*
			if ($reason == 'request') {
				$id = $request->move_id;
				$movement = InventoryMovement::find($request->move_id);		

				if ($request->type=='indent') {
						$find = 'Find indent request';
						$helper = new PurchaseHelper();
						$purchases = $helper->backOrder('indent_request');
						$purchases = $purchases->where('store_code', $movement->target_store);
						if (Auth::user()->author_id==18) {
								$purchases = $purchases->where('supplier_code', 'pmc_pharmacy');
						} else {
								$purchases = $purchases->where('supplier_code', 'pmc_purchase');
						}
				} else {
						$find = 'Find purchase request';
						$purchases = Purchase::where('document_code', '=', 'purchase_request')
								->where('status_code', '=', 'approved')
								->orderBy('purchase_id', 'desc');
				}
			}
			 */
			if ($reason == 'request') {
					$id = $request->move_id;
					$movement = InventoryMovement::find($request->move_id);		

					$purchases = Purchase::where('document_code', 'like', '%_request')
							->whereNull('status_code')
							->orderBy('purchase_id', 'desc');

					if (Auth::user()->author_id==18) {
							$purchases = $purchases->where('supplier_code', 'pmc_pharmacy');
					} else {
							$purchases = $purchases->where('supplier_code', 'pmc_purchase');
					}

					$find = 'Find purchase or indent request';
			}

			$purchases = $purchases->paginate($this->paginateValue);

			if ($reason == 'stock') {
				$movement = InventoryMovement::find($request->move_id);		
				$id = $request->move_id;
			}


			return view('purchases.master_document', [
					'purchases'=>$purchases,
					'purchase'=>$purchase,
					'id'=>$id,
					'movement'=>$movement,
					'reason'=>$reason,
					'reload'=>$request->reload,
					'search'=>null,
					'move_id'=>$request->move_id?:null,
					'find'=>$find,
					'type'=>$request->type,
			]);
	}


	public function masterSearch(Request $request) 
	{
			$find = 'Find';
			$id = null;
			$purchase = null;
			$movement = null;
			$reason = $request->reason?:null;

			$purchases = Purchase::orderBy('purchase_id', 'desc')
					->where('purchase_id', '<>', $id)
					->where('purchase_posted','=', 1);

			$purchases = $purchases
					->where(function ($query) use ($request) {
							$query->whereNull('status_code')
									->orWhere('status_code','!=', 'declined');
					});


			if ($reason == 'purchase') {
					$purchase = Purchase::find($request->purchase_id);
					//$purchases = $purchases->where('supplier_code', '=', $purchase->supplier_code);
					if (!empty($request->search)) {
						$purchases = $purchases->where('purchase_number', 'like', '%'.trim($request->search).'%');
					}
					$purchases = $purchases->where('author_id', Auth::user()->author_id);
					$id = $request->purchase_id;
			}

			if ($reason == 'request') {
					/*
					if ($request->type=='indent') {
							$purchases = Purchase::where('document_code', '=', 'indent_request');
					} else {
							$purchases = Purchase::where('document_code', '=', 'purchase_request');
					}
					 */
					$purchases = Purchase::where('document_code', 'like', '%_request')
							->whereNull('status_code')
							->orderBy('purchase_id', 'desc');

					if (Auth::user()->author_id==18) {
							$purchases = $purchases->where('supplier_code', 'pmc_pharmacy');
					} else {
							$purchases = $purchases->where('supplier_code', 'pmc_purchase');
					}

					if (!empty($request->search)) {
							$purchases = $purchases->where('purchase_number', 'like', '%'.trim($request->search).'%');
					}
					$id = $request->id;
					$movement = InventoryMovement::find($id);		
					$find = 'Find purchase or indent request';
			}

			$purchases = $purchases->paginate($this->paginateValue);

			if ($reason == 'stock') {
				$movement = InventoryMovement::find($request->move_id);		
				$id = $request->move_id;
			}

			return view('purchases.master_document', [
					'purchases'=>$purchases,
					'purchase'=>$purchase,
					'id'=>$id,
					'movement'=>$movement,
					'reason'=>$reason,
					'search'=>$request->search,
					'reload'=>null,
					'move_id'=>$request->move_id?:null,
					'find'=>$find,
					'type'=>$request->type,
			]);
	}

	public function create(Request $request)
	{
			$purchase = new Purchase();
			$purchase->store_code = Auth::User()->defaultStore($request);
			$purchase->purchase_date = DojoUtility::today();

			$suppliers = Supplier::all()->sortBy('supplier_name')->lists('supplier_name', 'supplier_code')->prepend('','');

			if (Auth::user()->author_id == 7) {
				$suppliers = [''=>'', 'pmc_pharmacy'=>'Pharmacy Department', 'pmc_purchase'=>'Purchase Department'];
				$purchase->document_code = 'indent_request';
			}

			return view('purchases.create', [
					'purchase' => $purchase,
					'supplier' => $suppliers,
					'documents' => PurchaseDocument::all()->sortBy('document_name')->lists('document_name', 'document_code')->prepend('',''),
					'store'=>Auth::user()->storeList()->prepend('',''),
					'store_code' => Auth::user()->defaultStore($request),
					]);
	}

	public function store(Request $request) 
	{
			$purchase = new Purchase();
			$valid = $purchase->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$purchase = new Purchase($request->all());
					$purchase->author_id = Auth::user()->author_id;
					$purchase->username = Auth::user()->username;
					$purchase->save();
					$this->updatePurchaseNumber($purchase->purchase_id);
					Session::flash('message', 'Record successfully created.');
					return redirect('/purchase_lines/show/'.$purchase->purchase_id);
			} else {
					return redirect('/purchases/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function updatePurchaseNumber($purchase_id) {


			$purchase = Purchase::findOrFail($purchase_id);
			$prefix = $purchase->document->document_prefix;

			$table = 'rn_'.$purchase->document_code;

			DB::insert('insert into '.$table.' (purchase_id) values (?)',
					[$purchase_id]);

			$results = DB::select('select * from '.$table.' where purchase_id='.$purchase_id);
			$id = $results[0]->id;

			$number = str_pad($id, 8, '0', STR_PAD_LEFT);

			$postfix = Auth::user()->authorization->document_postfix;
			if (!empty($postfix)) {
					$postfix = '-'.$postfix;
			}
			$purchase->purchase_number = $prefix. '-'.$number.''.$postfix;
			$purchase->save();

			$affected = DB::update('update '.$table.' set document_number= ? where id=?', [$purchase->purchase_number, $id]);
			Log::info($affected);

	}


	public function edit(Request $request, $id) 
	{
			$purchase = Purchase::findOrFail($id);

			$suppliers = Supplier::all()->sortBy('supplier_name')->lists('supplier_name', 'supplier_code')->prepend('','');

			if (Auth::user()->author_id == 7) {
				$suppliers = [''=>'', 'pmc_pharmacy'=>'Pharmacy Department', 'pmc_purchase'=>'Purchase Department'];
			}

			return view('purchases.edit', [
					'purchase'=>$purchase,
					'supplier' => $suppliers,
					'documents' => PurchaseDocument::all()->sortBy('document_name')->lists('document_name', 'document_code')->prepend('',''),
					'store'=>Auth::user()->storeList()->prepend('',''),
					'store_code' => Auth::user()->defaultStore($request),
					]);
	}

	public function update(Request $request, $id) 
	{
			$purchase = Purchase::findOrFail($id);
			$purchase->fill($request->input());


			$valid = $purchase->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$purchase->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/purchases/id/'.$id);
			} else {
					return redirect('/purchases/'.$id.'/edit')
							->withErrors($valid)
							->withInput();
			}
	}
	
	public function delete($id)
	{
		$purchase = Purchase::findOrFail($id);
		return view('purchases.destroy', [
			'purchase'=>$purchase
			]);

	}
	public function destroy($id)
	{	
			PurchaseLine::where('purchase_id', $id)->delete();
			Purchase::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/purchases');
	}
	
	public function search(Request $request)
	{
			$purchases = Purchase::orderBy('purchase_posted')
							->orderBy('purchase_id', 'desc');

			$store_code = Auth::User()->defaultStore($request);

			if (!empty($request->status_code)) {
					if ($request->status_code == 'indent_request') {
							$helper = new PurchaseHelper();
							//$purchases = $helper->backOrder('indent_request');
							$purchases = $helper->openRequest('indent_request');
							if (Auth::user()->authorization->indent_request == 0) {
									if ($store_code) {
											$purchases = $purchases->where('store_code', $store_code);
									}
							}
					}
					if ($request->status_code == 'open') {
							$purchases = Purchase::where('purchase_posted', 0)
									->where('author_id', '=', Auth::user()->author_id);
							if ($store_code) {
									$purchases = $purchases->where('store_code', $store_code);
							}
					}

					if ($request->status_code == 'purchase_request') {
							$purchases = Purchase::whereNull('status_code')
											->where('purchase_posted', 1)
											->where('document_code', '=', 'purchase_request');
							if (Auth::user()->authorization->purchase_request == 0) {
									if ($store_code) {
											$purchases = $purchases->where('store_code', $store_code);
									}
							}
					}

			} else {
					$purchases = $purchases->where('author_id', '=', Auth::user()->author_id);

					if (!empty($request->document_code)) {
							$purchases = $purchases->where('document_code', '=', $request->document_code);
					}

					if ($store_code) {
							$purchases = $purchases->where('store_code', $store_code);
					}

					if (Auth::user()->authorization->indent_request==1 || Auth::user()->authorization->purchase_request==1) {
							if ($request->document_code=='indent_request' || $request->document_code=='purchase_request') {
									$purchases = Purchase::orderBy('purchase_posted')
													->where('document_code', $request->document_code)
													->orderBy('purchase_id', 'desc');

									if (Auth::user()->author_id == 18) {
											$purchases = $purchases->where('supplier_code', 'pmc_pharmacy');
									} else {
											$purchases = $purchases->where('supplier_code', 'pmc_purchase');
									}
							}
					}
			}

			if (!empty($request->search)) {
					$purchases = $purchases->where('purchase_number','like','%'.trim($request->search).'%');
			}

			$purchases = $purchases->paginate($this->paginateValue);

			return view('purchases.index', [
					'purchases'=>$purchases,
					'search'=>$request->search,
					'document_code'=>$request->document_code,
					'documents' => PurchaseDocument::all()->sortBy('document_name')->lists('document_name', 'document_code')->prepend('',''),
					'purchase_helper' => new PurchaseHelper(),
					'author_id'=>Auth::user()->author_id,
					'status_code'=>$request->status_code,
					'store_code'=>$store_code,
					]);
	}

	public function searchById(Request $request, $id)
	{
			$purchases = Purchase::find($id)
					->orderBy('purchase_posted')
					->where('author_id', '=', Auth::user()->author_id)
					->orderBy('purchase_id', 'asc');

			$store_code = Auth::User()->defaultStore($request);
			if (!empty($store_code)) {
					$purchases = $purchases->where('store_code', $store_code);
			}

			$purchases = $purchases->paginate($this->paginateValue);

			return view('purchases.index', [
					'purchases'=>$purchases,
					'documents' => PurchaseDocument::all()->sortBy('document_name')->lists('document_name', 'document_code')->prepend('',''),
					'document_code' => null,
					'author_id'=>Auth::user()->author_id,
					'purchase_helper' => new PurchaseHelper(),
					'store_code'=>$store_code,
					'status_code'=>null,
					'search'=>null,
			]);
	}

}
