<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Consultation;
use Log;
use DB;
use Session;
use Auth;
use App\Patient;
use App\Order;
use App\OrderPost;
use App\Encounter;

class ConsultationController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$consultations = DB::table('consultations')
					->orderBy('consultation_status')
					->paginate($this->paginateValue);
			return view('consultations.index', [
					'consultations'=>$consultations
			]);
	}

	public function create(Request $request)
	{
			$consultation = DB::select("select consultation_id, encounter_id from consultations 
					where user_id = ".Auth::user()->id."
					and consultation_status=0
					and encounter_id = ".$request->encounter_id);

			$consultation_id=0;
			if (!empty($consultation)) {
					$consultation_id = $consultation[0]->consultation_id;
			}

			$notes = Consultation::where('encounter_id',$request->encounter_id)
					->where('consultation_id','!=', $consultation_id)
					->orderBy('created_at','desc')
					->get();
			
			
			if (empty($consultation)==true) {
					Log::info("New consultation");
					$consultation = new Consultation();
					$consultation->user_id = Auth::user()->id;
					$consultation->encounter_id = $request->encounter_id;
					$consultation->save();
					return view('consultations.edit', [
						'consultation'=>$consultation,
						'tab'=>'clinical',
						'notes'=>$notes,
					]);
			} else {
					Log::info("Edit consultation");
					$consultation = Consultation::find($consultation[0]->consultation_id);
					return view('consultations.edit', [
						'consultation'=>$consultation,
						'tab'=>'clinical',
						'notes'=>$notes,
					]);
			};
	}

	public function store(Request $request) 
	{
			$consultation = new Consultation();
			$valid = $consultation->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$consultation = new Consultation($request->all());
					$consultation->consultation_id = $request->consultation_id;
					$consultation->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/consultations/id/'.$consultation->consultation_id);
			} else {
					return redirect('/consultations/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function close($id)
	{
			$consultation = Consultation::findOrFail($id);
			$consultation->consultation_status = 1;
			$consultation->save();

			$post = new OrderPost();
			$post->consultation_id = $id;
			$post->save();

			Order::where('consultation_id','=',$id)
					->where('post_id','=',0)
					->update(['post_id'=>$post->post_id]);

			return redirect('/queues');
	}

	public function edit($id) 
	{
			$consultation = Consultation::findOrFail($id);
			$notes = Consultation::where('encounter_id',$consultation->encounter_id)
					->where('consultation_id','<>', $id)
					->orderBy('created_at','desc')
					->get();
			return view('consultations.edit', [
					'consultation'=>$consultation,
					'tab'=>'clinical',
					'notes'=>$notes,
					]);
	}

	public function update(Request $request, $id) 
	{
			$consultation = Consultation::findOrFail($id);
			$consultation->fill($request->input());


			$valid = $consultation->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$consultation->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/consultations/'.$id.'/edit');
			} else {
					return view('consultations.edit', [
							'consultation'=>$consultation,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$consultation = Consultation::findOrFail($id);
		return view('consultations.destroy', [
			'consultation'=>$consultation
			]);

	}
	public function destroy($id)
	{	
			Consultation::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/consultations');
	}
	
	public function search(Request $request)
	{
			$consultations = DB::table('consultations')
					->where('consultation_status','like','%'.$request->search.'%')
					->orWhere('consultation_id', 'like','%'.$request->search.'%')
					->orderBy('consultation_status')
					->paginate($this->paginateValue);

			return view('consultations.index', [
					'consultations'=>$consultations,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$consultations = DB::table('consultations')
					->where('consultation_id','=',$id)
					->paginate($this->paginateValue);

			return view('consultations.index', [
					'consultations'=>$consultations
			]);
	}
	
}
