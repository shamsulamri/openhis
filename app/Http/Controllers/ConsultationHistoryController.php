<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\ConsultationHistory;
use Log;
use DB;
use Session;
use App\History;
use App\Consultation;

class ConsultationHistoryController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$histories = History::all()->sortBy('history_name');
			$consultation = Consultation::findOrFail(Session::get('consultation_id'));

			$consultation_histories = ConsultationHistory::where('patient_id', $consultation->patient_id)
					->pluck('history_note', 'history_code');

			return view('consultation_histories.index', [
					'consultation_histories'=>$consultation_histories,
					'consultation'=>$consultation,
					'patient'=>$consultation->encounter->patient,
					'histories'=>$histories,
			]);
	}

	public function post(Request $request)
	{
			Log::info("Post....");
			$history_note = ConsultationHistory::where('patient_id', $request->patient_id)	
					->where('history_code', $request->history_code)
					->first();

			if (empty($history_note)) {
					$history_note = new ConsultationHistory();
					$history_note->patient_id = $request->patient_id;
					$history_note->history_code = $request->history_code;
			}

			$history_note->history_note = $request->history_note;
			$history_note->save();

			Log::info($history_note);
			return $request->history_code." saved...";

	}

	public function save(Request $request)
	{
			Log::info("Save....");
			$consultation = Consultation::findOrFail(Session::get('consultation_id'));
			$histories = History::all()->sortBy('history_name');
			foreach ($histories as $history) {
				$history_note = ConsultationHistory::where('patient_id', $consultation->patient_id)	
									->where('history_code', $history->history_code)
									->first();

				if (empty($history_note)) {
					$history_note = new ConsultationHistory();
					$history_note->patient_id = $consultation->patient_id;
					$history_note->history_code = $history->history_code;
				}

				$history_note->history_note = $request[$history->history_code];
				$history_note->save();
			}

			Session::flash('message', 'Record successfully created.');
			return redirect('/consultation_histories');
	}

	public function create()
	{
			$consultation_history = new ConsultationHistory();
			return view('consultation_histories.create', [
					'consultation_history' => $consultation_history,
					'history' => History::all()->sortBy('history_name')->lists('history_name', 'history_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$consultation_history = new ConsultationHistory();
			$valid = $consultation_history->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$consultation_history = new ConsultationHistory($request->all());
					$consultation_history->history_id = $request->history_id;
					$consultation_history->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/consultation_histories/id/'.$consultation_history->history_id);
			} else {
					return redirect('/consultation_histories/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$consultation_history = ConsultationHistory::findOrFail($id);
			return view('consultation_histories.edit', [
					'consultation_history'=>$consultation_history,
					'history' => History::all()->sortBy('history_name')->lists('history_name', 'history_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$consultation_history = ConsultationHistory::findOrFail($id);
			$consultation_history->fill($request->input());


			$valid = $consultation_history->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$consultation_history->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/consultation_histories/id/'.$id);
			} else {
					return view('consultation_histories.edit', [
							'consultation_history'=>$consultation_history,
					'history' => History::all()->sortBy('history_name')->lists('history_name', 'history_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$consultation_history = ConsultationHistory::findOrFail($id);
		return view('consultation_histories.destroy', [
			'consultation_history'=>$consultation_history
			]);

	}
	public function destroy($id)
	{	
			ConsultationHistory::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/consultation_histories');
	}
	
	public function search(Request $request)
	{
			$consultation_histories = ConsultationHistory::where('history_id','like','%'.$request->search.'%')
					->orWhere('history_id', 'like','%'.$request->search.'%')
					->orderBy('history_id')
					->paginate($this->paginateValue);

			return view('consultation_histories.index', [
					'consultation_histories'=>$consultation_histories,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$consultation_histories = ConsultationHistory::where('history_id','=',$id)
					->paginate($this->paginateValue);

			return view('consultation_histories.index', [
					'consultation_histories'=>$consultation_histories
			]);
	}
}
