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
use App\DischargeHelper;

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

			$helper = new DischargeHelper();
			$helper->populateSummary($id);
			return redirect('/discharge/summary/'.$id);
	}


}
