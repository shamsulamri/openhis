<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Bed;
use Log;
use DB;
use Session;
use App\WardClass;
use App\Ward;
use App\Room;
use App\BedStatus;
use App\Gender;
use App\Department;
use App\EncounterType;
use App\BedHelper;
use App\Product;

class BedController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$beds = DB::table('beds as a')
					->leftjoin('wards as b', 'b.ward_code','=', 'a.ward_code')
					->leftjoin('ward_classes as c', 'a.class_code','=', 'c.class_code')
					->leftjoin('bed_statuses as d', 'd.status_code','=', 'a.status_code')
					->orderBy('ward_name')
					->orderBy('class_name')
					->orderBy('bed_name')
					->paginate($this->paginateValue);

			return view('beds.index', [
					'beds'=>$beds,
					'wards' => Ward::all()->sortBy('ward_name')->lists('ward_name', 'ward_code')->prepend('',''),
					'class' => WardClass::all()->sortBy('class_name')->lists('class_name', 'class_code')->prepend('',''),
					'ward_code' => null,
					'class_code' => null,
					'bedHelper' => new BedHelper(),
			]);
	}

	public function create()
	{
			$bed = new Bed();
			return view('beds.create', [
					'bed' => $bed,
					'class' => WardClass::all()->sortBy('class_name')->lists('class_name', 'class_code')->prepend('',''),
					'ward' => Ward::all()->sortBy('ward_name')->lists('ward_name', 'ward_code')->prepend('',''),
					'room' => Room::all()->sortBy('room_name')->lists('room_name', 'room_code')->prepend('',''),
					'status' => BedStatus::all()->sortBy('status_name')->lists('status_name', 'status_code')->prepend('',''),
					'gender' => Gender::all()->sortBy('gender_name')->lists('gender_name', 'gender_code')->prepend('',''),
					'department' => Department::all()->sortBy('department_name')->lists('department_name', 'department_code')->prepend('',''),
					'encounter_type' => EncounterType::all()->sortBy('encounter_name')->lists('encounter_name', 'encounter_code')->prepend('',''),
					]);
	}

	public function store(Request $request) 
	{
			$bed = new Bed();
			$valid = $bed->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$bed = new Bed($request->all());
					$bed->bed_code = $request->bed_code;
					$bed->save();
					$product = new Product();
					$product->product_code = $bed->bed_code;
					$product->product_name = $bed->bed_name;
					$product->product_sold = 1;
					$product->product_sale_price = 99;
					$product->product_unit_charge=1;
					$product->save();
					Session::flash('message', 'Record successfully created. Please update the product price information.');
					return redirect('/beds/id/'.$bed->bed_code);
			} else {
					return redirect('/beds/create')
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$bed = Bed::findOrFail($id);
			return view('beds.edit', [
					'bed'=>$bed,
					'class' => WardClass::all()->sortBy('class_name')->lists('class_name', 'class_code')->prepend('',''),
					'ward' => Ward::all()->sortBy('ward_name')->lists('ward_name', 'ward_code')->prepend('',''),
					'room' => Room::all()->sortBy('room_name')->lists('room_name', 'room_code')->prepend('',''),
					'status' => BedStatus::all()->sortBy('status_name')->lists('status_name', 'status_code')->prepend('',''),
					'gender' => Gender::all()->sortBy('gender_name')->lists('gender_name', 'gender_code')->prepend('',''),
					'department' => Department::all()->sortBy('department_name')->lists('department_name', 'department_code')->prepend('',''),
					'encounter_type' => EncounterType::all()->sortBy('encounter_name')->lists('encounter_name', 'encounter_code')->prepend('',''),
					]);
	}

	public function update(Request $request, $id) 
	{
			$bed = Bed::findOrFail($id);
			$bed->fill($request->input());


			$valid = $bed->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$bed->save();
					$product = Product::find($bed->bed_code);
					$product->product_name = $bed->bed_name;
					$product->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/beds');
			} else {
					return view('beds.edit', [
							'bed'=>$bed,
							'class' => WardClass::all()->sortBy('class_name')->lists('class_name', 'class_code')->prepend('',''),
							'wards' => Ward::all()->sortBy('ward_name')->lists('ward_name', 'ward_code')->prepend('',''),
							'room' => Room::all()->sortBy('room_name')->lists('room_name', 'room_code')->prepend('',''),
							'status' => BedStatus::all()->sortBy('status_name')->lists('status_name', 'status_code')->prepend('',''),
							'gender' => Gender::all()->sortBy('gender_name')->lists('gender_name', 'gender_code')->prepend('',''),
							'department' => Department::all()->sortBy('department_name')->lists('department_name', 'department_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$bed = Bed::findOrFail($id);
		return view('beds.destroy', [
			'bed'=>$bed
			]);

	}
	public function destroy($id)
	{	
			Bed::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/beds');
	}
	
	public function search(Request $request)
	{
			$beds = DB::table('beds as a')
					->leftjoin('wards as b', 'b.ward_code','=', 'a.ward_code')
					->leftjoin('ward_classes as c', 'a.class_code','=', 'c.class_code')
					->leftjoin('bed_statuses as d', 'd.status_code','=', 'a.status_code')
					->where('a.ward_code','like', '%'.$request->ward_code.'%')
					->where('a.class_code','like', '%'.$request->class_code.'%')
					->where(function ($query) use ($request) {
							$query->where('bed_name','like','%'.$request->search.'%')
								  ->orWhere('bed_code', 'like','%'.$request->search.'%');
					});

			$beds = $beds->orderBy('ward_name')
						 ->orderBy('class_name')
						 ->orderBy('bed_name')
						 ->paginate($this->paginateValue);

			return view('beds.index', [
					'beds'=>$beds,
					'search'=>$request->search,
					'wards' => Ward::all()->sortBy('ward_name')->lists('ward_name', 'ward_code')->prepend('',''),
					'class' => WardClass::all()->sortBy('class_name')->lists('class_name', 'class_code')->prepend('',''),
					'ward_code' => $request->ward_code,
					'class_code' => $request->class_code,
					'bedHelper' => new BedHelper(),
					]);
	}

	public function searchById($id)
	{
			$beds = DB::table('beds as a')
					->leftjoin('wards as b', 'b.ward_code','=', 'a.ward_code')
					->leftjoin('ward_classes as c', 'a.class_code','=', 'c.class_code')
					->leftjoin('bed_statuses as d', 'd.status_code','=', 'a.status_code')
					->where('bed_code','=',$id)
					->orderBy('ward_name')
					->orderBy('class_name')
					->orderBy('bed_name')
					->paginate($this->paginateValue);

			return view('beds.index', [
					'beds'=>$beds,
					'wards' => Ward::all()->sortBy('ward_name')->lists('ward_name', 'ward_code')->prepend('',''),
					'class' => WardClass::all()->sortBy('class_name')->lists('class_name', 'class_code')->prepend('',''),
					'ward_code' => null,
					'class_code' => null,
					'bedHelper' => new BedHelper(),
			]);
	}

	public function bedVacant($ward_code, $class_code) 
	{
			$sql = "
				select count(*) as count
				from beds a
				left join (
						select a.encounter_id,discharge_id, bed_code from admissions a
						left join discharges b on (a.encounter_id = b.encounter_id)
						where discharge_id is null
				) as b on (a.bed_code = b.bed_code) 
				where discharge_id is null
				and ward_code='".$ward_code."'
				and a.class_code = '".$class_code."'
				and encounter_id is null";
			$beds = DB::select($sql);
			return $beds[0]->count;
	}

}
