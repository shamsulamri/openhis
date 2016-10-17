<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BedController;
use App\BedBooking;
use Log;
use DB;
use Session;
use App\Admission;
use App\WardClass;
use App\Patient;
use App\Bed;
use App\Ward;
use Carbon\Carbon;
use App\DojoUtility;
use Auth;

class BedBookingController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index(Request $request)
	{
			$is_preadmission = False;
			if ($request->type=='preadmission') {
					$is_preadmission = True;
			} else {
					if (empty($request->cookie('ward'))) {
							Session::flash('message', 'Ward not set. Please select your ward.');
							return redirect('/wards');
					}
			}

			$bed_bookings = DB::table('bed_bookings as a')
					->select(['d.ward_code', 'book_id', 'book_date', 'a.created_at', 'bed_name', 'a.admission_id','patient_mrn', 'patient_name', 'b.class_name', 'a.class_code','ward_name'])
					->leftJoin('ward_classes as b', 'b.class_code','=', 'a.class_code')
					->leftJoin('patients as c', 'c.patient_id','=', 'a.patient_id')
					->leftJoin('wards as d', 'd.ward_code', '=', 'a.ward_code')
					->leftJoin('admissions as e', 'e.admission_id','=', 'a.admission_id')
					->leftJoin('encounters as f', 'f.encounter_id', '=', 'e.encounter_id')
					->leftJoin('discharges as g', 'g.encounter_id', '=', 'f.encounter_id')
					->leftJoin('beds as h', 'h.bed_code', '=', 'a.bed_code')
					->where('book_date','>=', Carbon::today())
					->orderBy('a.book_date');


			if ($is_preadmission) {
					$bed_bookings = $bed_bookings->whereNull('f.encounter_id');
			} else {
					$bed_bookings = $bed_bookings->whereNull('discharge_id');
			}

			if (!empty($request->search)) {
					$bed_bookings = $bed_bookings->where(function ($query) use ($request) {
								$query	->where('patient_mrn','like','%'.$request->search.'%')
										->orWhere('patient_name','like','%'.$request->search.'%');
					});
			}

			$bed_bookings = $bed_bookings->paginate($this->paginateValue);
			
			return view('bed_bookings.index', [
					'bed_bookings'=>$bed_bookings,
					'bedController' => new BedController(),
					'ward' => Ward::where('ward_code', $request->cookie('ward'))->first(),
					'is_preadmission' => $is_preadmission,
			]);
	}

	public function create(Request $request, $patient_id, $admission_id = null)
	{
			$bed_booking = new BedBooking();
			$bed_booking->patient_id = $patient_id;
			$bed_booking->book_date = DojoUtility::now();
			$bed_booking->admission_id = $admission_id;
			$bed_booking->ward_code = $request->ward_code;
			$bed_booking->class_code = $request->class_code;
			$bed_booking->bed_code = $request->bed_code;

			$title = "Bed Reservation";
			if ($request->book=='preadmission') $title = "Preadmission";
			if ($admission_id != null) {
					$admission = Admission::find($admission_id);
					//$bed_booking->bed_code = $admission->bed_code;
			}
			return view('bed_bookings.create', [
					'bed_booking' => $bed_booking,
					'bed' => Bed::where('ward_code', $request->ward_code)
								->where('class_code', $request->class_code)
								->orderBY('bed_name')->lists('bed_name', 'bed_code')->prepend('',''),
					'ward' => Ward::all()->sortBy('ward_name')->lists('ward_name', 'ward_code')->prepend('',''),
					'class' => WardClass::all()->sortBy('class_name')->lists('class_name', 'class_code')->prepend('',''),
					'patient' => Patient::find($bed_booking->patient_id),
					'title' => $title,
					'admission_id' => $admission_id,
					]);
	}

	public function store(Request $request) 
	{
			$bed_booking = new BedBooking();
			$valid = $bed_booking->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$bed_booking = new BedBooking($request->all());
					$bed_booking->bed_code = $request->bed;
					$bed_booking->book_id = $request->book_id;
					$bed_booking->save();
					Session::flash('message', 'Record successfully created.');
					if (Auth::user()->can('module-ward')) {
							return redirect('/bed_bookings');
					} else {
							return redirect('/bed_bookings?type=preadmission');
					}

			} else {
					return redirect('/bed_bookings/create/'.$request->patient_id.'/'.$request->admission_id)
							->withErrors($valid)
							->withInput();
			}
	}

	public function edit($id) 
	{
			$bed_booking = BedBooking::findOrFail($id);
			return view('bed_bookings.edit', [
					'bed_booking'=>$bed_booking,
					'patient' => Patient::find($bed_booking->patient_id),
					'class' => WardClass::all()->sortBy('class_name')->lists('class_name', 'class_code')->prepend('',''),
					'ward' => Ward::all()->sortBy('ward_name')->lists('ward_name', 'ward_code')->prepend('',''),
					'bed' => Bed::where('ward_code', $bed_booking->ward_code)
								->where('class_code', $bed_booking->class_code)
								->orderBY('bed_name')->lists('bed_name', 'bed_code')->prepend('',''),
					'admission_id' => $bed_booking->admission_id,
					]);
	}

	public function update(Request $request, $id) 
	{
			$bed_booking = BedBooking::findOrFail($id);
			$bed_booking->fill($request->input());


			$valid = $bed_booking->validate($request->all(), $request->_method);	

			if ($valid->passes()) {
					$bed_booking->save();
					Session::flash('message', 'Record successfully updated.');
					return redirect('/bed_bookings');
			} else {
					return view('bed_bookings.edit', [
							'bed_booking'=>$bed_booking,
							'patient' => Patient::find($bed_booking->patient_id),
							'class' => WardClass::all()->sortBy('class_name')->lists('class_name', 'class_code')->prepend('',''),
							])
							->withErrors($valid);			
			}
	}
	
	public function delete($id)
	{
		$bed_booking = BedBooking::findOrFail($id);
		$patient = Patient::find($bed_booking->patient_id);
		return view('bed_bookings.destroy', [
				'bed_booking'=>$bed_booking,
				'patient'=>$patient,
			]);

	}
	public function destroy($id)
	{	
			BedBooking::find($id)->delete();
			Session::flash('message', 'Record deleted.');
			return redirect('/bed_bookings');
	}
	
	public function search(Request $request)
	{
			$bed_bookings = DB::table('bed_bookings')
					->where('patient_id','like','%'.$request->search.'%')
					->orWhere('book_id', 'like','%'.$request->search.'%')
					->orderBy('patient_id')
					->paginate($this->paginateValue);

			return view('bed_bookings.index', [
					'bed_bookings'=>$bed_bookings,
					'search'=>$request->search
					]);
	}

	public function searchById($id)
	{
			$bed_bookings = DB::table('bed_bookings')
					->where('book_id','=',$id)
					->paginate($this->paginateValue);

			return view('bed_bookings.index', [
					'bed_bookings'=>$bed_bookings
			]);
	}

}
