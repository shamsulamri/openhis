<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

class PurchaseController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$purchases = Purchase::orderBy('purchase_posted')
					->orderBy('purchase_id', 'asc')
					->paginate($this->paginateValue);
			return view('purchases.index', [
					'purchases'=>$purchases
			]);
	}

	public function masterDocument(Request $request, $id = null)
	{
			$purchase = null;
			$movement = null;
			$reason = $request->reason?:null;

			$purchases = Purchase::orderBy('purchase_id', 'desc')
					->where('purchase_id', '<>', $id)
					->where('purchase_posted', 1);

			if ($reason == 'purchase') {
					$purchase = Purchase::find($request->purchase_id);
					$purchases = $purchases->where('supplier_code', '=', $purchase->supplier_code);
					$id = $request->purchase_id;
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
			]);
	}

	public function masterSearch(Request $request) 
	{
			$id = null;
			$purchase = null;
			$movement = null;
			$reason = $request->reason?:null;

			$purchases = Purchase::orderBy('purchase_id', 'desc')
					->where('purchase_number', 'like','%'.$request->search.'%')
					->where('purchase_posted', 1);

			if ($reason == 'purchase') {
					$purchase = Purchase::find($request->purchase_id);
					$purchases = $purchases->where('supplier_code', '=', $purchase->supplier_code);
					$id = $request->purchase_id;
			}

			$purchases = $purchases->paginate($this->paginateValue);

			if ($reason == 'stock') {
				$movement = InventoryMovement::find($request->move_id);		
				$id = $request->move_id;
			}

			return view('purchases.master_document', [
					'purchases'=>$purchases,
					'purchase'=>$purchase,
					'id'=>$request->id,
					'movement'=>$movement,
					'reason'=>$reason,
					'search'=>$request->search,
			]);
	}

	public function create()
	{
			$purchase = new Purchase();
			$purchase->purchase_date = DojoUtility::today();

			return view('purchases.create', [
					'purchase' => $purchase,
					'supplier' => Supplier::all()->sortBy('supplier_name')->lists('supplier_name', 'supplier_code')->prepend('',''),
					'documents' => PurchaseDocument::all()->sortBy('document_name')->lists('document_name', 'document_code')->prepend('',''),
					'store' => Store::where('store_receiving','=',1)->orderBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$purchase = new Purchase();
			$valid = $purchase->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$purchase = new Purchase($request->all());
					$purchase->author_id = Auth::user()->author_id;
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

			$purchase->purchase_number = $prefix. '-'.$number;
			$purchase->save();

			$affected = DB::update('update '.$table.' set document_number= ? where id=?', [$purchase->purchase_number, $id]);
			Log::info($affected);

	}


	public function edit($id) 
	{
			$purchase = Purchase::findOrFail($id);
			return view('purchases.edit', [
					'purchase'=>$purchase,
					'supplier' => Supplier::all()->sortBy('supplier_name')->lists('supplier_name', 'supplier_code')->prepend('',''),
					'documents' => PurchaseDocument::all()->sortBy('document_name')->lists('document_name', 'document_code')->prepend('',''),
					'store' => Store::where('store_receiving','=',1)->orderBy('store_name')->lists('store_name', 'store_code')->prepend('',''),
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
					return view('purchases.edit', [
							'purchase'=>$purchase,
					'supplier' => Supplier::all()->sortBy('supplier_name')->lists('supplier_name', 'supplier_code')->prepend('',''),
							])
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
			Purchase::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/purchases');
	}
	
	public function search(Request $request)
	{
			$purchases = DB::table('purchases')
					->where('purchase_number','like','%'.$request->search.'%')
					->orWhere('purchase_id', 'like','%'.$request->search.'%')
					->orderBy('purchase_number')
					->paginate($this->paginateValue);

			return view('purchases.index', [
					'purchases'=>$purchases,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$purchases = Purchase::find($id)
					->paginate($this->paginateValue);

			return view('purchases.index', [
					'purchases'=>$purchases
			]);
	}

}
