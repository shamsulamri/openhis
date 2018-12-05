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

class PurchaseController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$purchases = Purchase::orderBy('purchase_id', 'desc')
					->paginate($this->paginateValue);
			return view('purchases.index', [
					'purchases'=>$purchases
			]);
	}

	public function masterDocument($id)
	{
			$purchase = Purchase::find($id);

			$purchases = Purchase::orderBy('purchase_id', 'desc')
					->where('supplier_code', '=', $purchase->supplier_code)
					->where('purchase_id', '<>', $id)
					->paginate($this->paginateValue);

			return view('purchases.master_document', [
					'purchases'=>$purchases,
					'purchase'=>$purchase,
					'purchase_id'=>$id,
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

	public function updatePurchaseNumber($id) {
			$purchase = Purchase::findOrFail($id);
			$prefix = Auth::user()->authorization->identification_prefix."-";
			$prefix = "";
			$number = str_pad($id, 8, '0', STR_PAD_LEFT);
			$purchase->purchase_number = $prefix.$purchase->document->document_prefix . '-'.$number;
			$purchase->save();
	}

	public function edit($id) 
	{
			$purchase = Purchase::findOrFail($id);
			return view('purchases.edit', [
					'purchase'=>$purchase,
					'supplier' => Supplier::all()->sortBy('supplier_name')->lists('supplier_name', 'supplier_code')->prepend('',''),
					'documents' => PurchaseDocument::all()->sortBy('document_name')->lists('document_name', 'document_code')->prepend('',''),
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
