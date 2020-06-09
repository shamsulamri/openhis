<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DischargeSummary;
use App\Order;
use App\Discharge;
use App\Encounter;
use Log;
use DB;
use Session;

class DischargeSummaryController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$discharge_summaries = DischargeSummary::orderBy('summary_treatment')
					->paginate($this->paginateValue);

			return view('discharge_summaries.index', [
					'discharge_summaries'=>$discharge_summaries
			]);
	}

	public function create()
	{
			$discharge_summary = new DischargeSummary();
			return view('discharge_summaries.create', [
					'discharge_summary' => $discharge_summary,
				
					]);
	}

	public function store(Request $request) 
	{
			$discharge_summary = new DischargeSummary();
			$valid = $discharge_summary->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$discharge_summary = new DischargeSummary($request->all());
					$discharge_summary->encounter_id = $request->encounter_id;
					$discharge_summary->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/discharge_summaries/id/'.$discharge_summary->encounter_id);
			} else {
					return redirect('/discharge_summaries/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$discharge_summary = DischargeSummary::findOrFail($id);
			return view('discharge_summaries.edit', [
					'discharge_summary'=>$discharge_summary,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$discharge_summary = DischargeSummary::findOrFail($id);
			$discharge_summary->fill($request->input());

			$valid = $discharge_summary->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$discharge_summary->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/consultations');
					return redirect('/discharge_summaries/id/'.$id);
			} else {
					return view('discharge_summaries.edit', [
							'discharge_summary'=>$discharge_summary,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$discharge_summary = DischargeSummary::findOrFail($id);
		return view('discharge_summaries.destroy', [
			'discharge_summary'=>$discharge_summary
			]);

	}
	public function destroy($id)
	{	
			DischargeSummary::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/discharge_summaries');
	}
	
	public function search(Request $request)
	{
			$discharge_summaries = DischargeSummary::where('summary_treatment','like','%'.$request->search.'%')
					->orWhere('encounter_id', 'like','%'.$request->search.'%')
					->orderBy('summary_treatment')
					->paginate($this->paginateValue);

			return view('discharge_summaries.index', [
					'discharge_summaries'=>$discharge_summaries,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$discharge_summaries = DischargeSummary::where('encounter_id','=',$id)
					->paginate($this->paginateValue);

			return view('discharge_summaries.index', [
					'discharge_summaries'=>$discharge_summaries
			]);
	}

	public function reset($id)
	{

			$discharge_summary = DischargeSummary::find($id);
			if ($discharge_summary) {
				DischargeSummary::find($id)->delete();
			}

			$discharge = Discharge::where('encounter_id', $id)->first();

			$treatments = Order::select('product_name')
					->where('encounter_id', $id)
					->whereIn('category_code', ['lab','imaging', 'physio_service'])
					->where('order_completed', 1)
					->leftjoin('products as b', 'b.product_code', '=', 'orders.product_code')
					->pluck('product_name');

			$drugs = Order::select('product_name')
					->where('encounter_id', $id)
					->whereIn('category_code', ['drugs'])
					->where('order_completed', 1)
					->leftjoin('products as b', 'b.product_code', '=', 'orders.product_code')
					->pluck('product_name');

			$procedures = Order::select('product_name')
					->where('encounter_id', $id)
					->whereIn('category_code', ['fee_procedure'])
					->where('order_completed', 1)
					->leftjoin('products as b', 'b.product_code', '=', 'orders.product_code')
					->pluck('product_name');

			$follow_up = Encounter::select('appointment_datetime', 'service_name')
							->leftjoin('appointments as b', 'b.patient_id', '=', 'encounters.patient_id')
							->leftjoin('appointment_services as c', 'c.service_id', '=', 'b.service_id')
							->where('encounter_id', $id)
							->where('appointment_datetime', '>', 'encounters.created_at')
							->pluck('service_name');

			$summary = new DischargeSummary();
			$summary->encounter_id = $id;
			$summary->summary_diagnosis = $discharge->discharge_summary;
			$summary->summary_treatment = $this->toList($treatments);
			$summary->summary_medication = $this->toList($drugs);
			$summary->summary_surgical = $this->toList($procedures);
			$summary->summary_follow_up = $this->toList($follow_up);
			$summary->save();

			return redirect('/discharge/summary/'.$id);
	}

	public function toList($items) {
			$list = "";
			$index = 1;
			foreach($items as $item) {
				$list = $list.$index.". ".$item;
				if ($index<sizeof($items)) {
						$list = $list."\n";
				}
				$index +=1;
			}

			return $list;
	}

}
