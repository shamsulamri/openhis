<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Input;

use App\Http\Requests;
use Auth;
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
use App\DojoUtility;
use App\WardDischarge;

class BedController extends Controller
{
	public $paginateValue=25;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index(Request $request)
	{

			$beds = Bed::select('*')
					->leftjoin('wards as b', 'b.ward_code','=', 'beds.ward_code')
					->leftjoin('ward_classes as c', 'beds.class_code','=', 'c.class_code')
					->leftjoin('bed_statuses as d', 'd.status_code','=', 'beds.status_code')
					->orderBy('ward_name')
					->orderBy('ward_level')
					->orderBy('beds.class_code')
					->orderBy('bed_code');

			if (Auth::user()->cannot('system-administrator')) {
					if (Auth::user()->can('module-ward')) {
							$ward_code = $request->cookie('ward');
							if ($ward_code) {
									$beds = $beds->where('beds.ward_code','=', $ward_code);
							}
					}
			}

			$beds = $beds->paginate($this->paginateValue);

			return view('beds.index', [
					'beds'=>$beds,
					'wards' => Ward::all()->sortBy('ward_name')->lists('ward_name', 'ward_code')->prepend('',''),
					'class' => WardClass::all()->sortBy('class_name')->lists('class_name', 'class_code')->prepend('',''),
					'ward_code' => null,
					'class_code' => null,
					'search'=> null,
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
					$bed->status_code = '01';
					$bed->save();

					$class = WardClass::find($bed->class_code);

					$product = new Product();
					$product->product_code = $bed->bed_code;
					$product->product_name = $bed->bed_name;
					$product->category_code = "srv";
					$product->product_sold = 1;
					$product->product_sale_price = $class->class_price;
					$product->order_form="1";
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
	
			if (Auth::user()->can('module-ward')) {
					if ($bed->status_code=='02' && $request->status_code=='01') {
							$now = DojoUtility::now();
							$ward_discharge = WardDischarge::where('bed_code', $id)
													->whereNull('housekeeping_datetime')
													->first();
							if ($ward_discharge) {
									$ward_discharge->housekeeping_datetime = DojoUtility::dateTimeWriteFormat($now);
									$ward_discharge->save();
							}
					}
			}

			$bed->fill($request->input());


			$valid = $bed->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$bed->save();
					$product = Product::find($bed->bed_code);
					if ($product) {
							$product->product_name = $bed->bed_name;
							$product->save();
					}
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
			$bed = Bed::find($id);
			$product = Product::find($id);
			if (!empty($product)) {
					$product->forceDelete();
			}
			Bed::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/beds');
	}
	
	public function search(Request $request)
	{
			$beds = Bed::select('*')
					->leftjoin('wards as b', 'b.ward_code','=', 'beds.ward_code')
					->leftjoin('ward_classes as c', 'beds.class_code','=', 'c.class_code')
					->leftjoin('bed_statuses as d', 'd.status_code','=', 'beds.status_code')
					->where('beds.ward_code','like', '%'.$request->ward_code.'%')
					->where('beds.class_code','like', '%'.$request->class_code.'%')
					->where(function ($query) use ($request) {
							$query->where('bed_name','like','%'.$request->search.'%')
								  ->orWhere('bed_code', 'like','%'.$request->search.'%');
					});

			$beds = $beds->orderBy('ward_name')
						 ->orderBy('ward_level')
					 	 ->orderBy('beds.class_code')
						 ->orderBy('bed_code')
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
			$beds = Bed::where('bed_code','=',$id)
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

	public function enquiry(Request $request)
	{
			$search = '%'.$request->search.'%';

			$sql = "
				select bed_name, class_name, ward_name, status_name, patient_name, patient_mrn, room_name, a.bed_code
				from beds as a
				left join wards as b on (a.ward_code = b.ward_code)
				left join ward_classes as c on (c.class_code = a.class_code)
				left join bed_statuses as d on (d.status_code = a.status_code)
				left join (
					select a.bed_code, patient_name, patient_mrn from admissions as a
					left join ward_discharges b on (a.encounter_id = b.encounter_id)
					left join encounters c on (c.encounter_id = a.encounter_id)
					left join patients d on (d.patient_id = c.patient_id)
					where discharge_id is null
				) as e on (e.bed_code = a.bed_code)
				left join ward_rooms as f on (f.room_code = a.room_code)
				where (bed_name like ? or a.bed_code like ?) 
			";

			if (!empty($request->ward_code)) {
				$sql = $sql.sprintf(" and a.ward_code = '%s' ", $request->ward_code);
			}

			if (!empty($request->status_code)) {
				$sql = $sql.sprintf(" and a.status_code = '%s' ", $request->status_code);
			}

			if (!empty($request->class_code)) {
				$sql = $sql.sprintf(" and a.class_code = '%s' ", $request->class_code);
			}

			$sql = $sql." order by ward_name, class_name, room_name, bed_name";

			$beds = DB::select($sql, [$search, $search]);

			if ($request->export_report) {
				$beds = collect($beds)->map(function($x){ return (array) $x; })->toArray(); 
				DojoUtility::export_report($beds);
			}
			
			$page = Input::get('page', 1); 
			$offSet = ($page * $this->paginateValue) - $this->paginateValue;
			$itemsForCurrentPage = array_slice($beds, $offSet, $this->paginateValue, true);

			$beds = new LengthAwarePaginator($itemsForCurrentPage, count($beds), 
					$this->paginateValue, 
					$page, 
					['path' => $request->url(), 
					'query' => $request->query()]
			);

			return view('beds.enquiry', [
					'beds'=>$beds,
					'search'=>$request->search,
					'wards' => Ward::all()->sortBy('ward_name')->lists('ward_name', 'ward_code')->prepend('',''),
					'class' => WardClass::all()->sortBy('class_name')->lists('class_name', 'class_code')->prepend('',''),
					'ward_code' => $request->ward_code,
					'class_code' => $request->class_code,
					'bedHelper' => new BedHelper(),
					'status' => BedStatus::all()->sortBy('status_name')->lists('status_name', 'status_code')->prepend('',''),
					'status_code'=>$request->status_code,
			]);

	}

	public function generate()
	{
			$beds = Bed::all();
			foreach($beds as $bed) {
					$product = new Product();
					$product->product_code = $bed->bed_code;
					$product->product_name = $bed->bed_name.", ".$bed->room['room_name'].", ". $bed->wardClass->class_name .", ". $bed->ward->ward_name;
					$product->category_code = "srv";
					$product->product_sold = 1;
					$product->product_sale_price = $bed->wardClass->class_price;
					$product->order_form="1";
					$product->save();
					//Log::info($product->product_name);
			}
			return "Ok";
	}
}
