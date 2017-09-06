<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\BedMovement;
use Log;
use DB;
use Session;

class BedMovementController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$bed_movements = DB::table('bed_movements')
					->orderBy('admission_id')
					->paginate($this->paginateValue);
			return view('bed_movements.index', [
					'bed_movements'=>$bed_movements
			]);
	}

	public function create()
	{
			$bed_movement = new BedMovement();
			return view('bed_movements.create', [
					'bed_movement' => $bed_movement,
				
					]);
	}

	public function store(Request $request) 
	{
			$bed_movement = new BedMovement();
			$valid = $bed_movement->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$bed_movement = new BedMovement($request->all());
					$bed_movement->move_id = $request->move_id;
					$bed_movement->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/bed_movements/id/'.$bed_movement->move_id);
			} else {
					return redirect('/bed_movements/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$bed_movement = BedMovement::findOrFail($id);
			return view('bed_movements.edit', [
					'bed_movement'=>$bed_movement,
				
					]);
	}

	public function update(Request $request, $id) 
	{
			$bed_movement = BedMovement::findOrFail($id);
			$bed_movement->fill($request->input());


			$valid = $bed_movement->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$bed_movement->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/bed_movements/id/'.$id);
			} else {
					return view('bed_movements.edit', [
							'bed_movement'=>$bed_movement,
				
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$bed_movement = BedMovement::findOrFail($id);
		return view('bed_movements.destroy', [
			'bed_movement'=>$bed_movement
			]);

	}
	public function destroy($id)
	{	
			BedMovement::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/bed_movements');
	}
	
	public function search(Request $request)
	{
			$bed_movements = DB::table('bed_movements')
					->where('admission_id','like','%'.$request->search.'%')
					->orWhere('move_id', 'like','%'.$request->search.'%')
					->orderBy('admission_id')
					->paginate($this->paginateValue);

			return view('bed_movements.index', [
					'bed_movements'=>$bed_movements,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$bed_movements = DB::table('bed_movements')
					->where('move_id','=',$id)
					->paginate($this->paginateValue);

			return view('bed_movements.index', [
					'bed_movements'=>$bed_movements
			]);
	}

	public function enquiry(Request $request)
	{
			$movements = array(''=>'','swap'=>'Swap','change'=>'Change','transfer'=>'Transfer');
			$bed_movements = BedMovement::leftjoin('encounters as b', 'b.encounter_id','=', 'bed_movements.encounter_id')
					->leftJoin('patients as c', 'c.patient_id', '=', 'b.patient_id')
					->leftJoin('beds as d', 'd.bed_code', '=', 'bed_movements.move_from')
					->leftJoin('beds as e', 'e.bed_code', '=', 'bed_movements.move_to')
					->where(function ($query) use ($request) {
							$query->where('patient_name','like', '%'.$request->search.'%')
									->orWhere('patient_mrn','like', '%'.$request->search.'%')
									->orWhere('b.encounter_id','=',$request->search);
					})
					->orderBy('bed_movements.encounter_id','move_id');

			if (!empty($request->move_code)) {
					$bed_movements = $bed_movements->where('d.bed_code','<>', DB::raw('e.bed_code'));
					switch ($request->move_code) {
						case  "swap":
							$bed_movements = $bed_movements->where('d.class_code','=', DB::raw('e.class_code'))
													->where('d.ward_code','=', DB::raw('e.ward_code'));
							break;
						case  "change":
							$bed_movements = $bed_movements->where('d.class_code','<>', DB::raw('e.class_code'))
													->where('d.ward_code','=', DB::raw('e.ward_code'));
							break;
						case  "transfer":
							$bed_movements = $bed_movements->where('d.ward_code','<>', DB::raw('e.ward_code'));
							break;
					}
			}

			//dd($bed_movements->toSql());
			$bed_movements = $bed_movements->paginate($this->paginateValue);


			return view('bed_movements.enquiry', [
					'bed_movements'=>$bed_movements,
					'search'=>$request->search,
					'movements'=>$movements,
					'move_code'=>$request->move_code
					]);
	}
}
