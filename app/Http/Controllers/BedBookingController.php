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
use App\User;
use App\Priority;
use Config;
use App\BedHelper;

class BedBookingController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index(Request $request)
	{
			$bed_bookings = DB::table('bed_bookings as a')
					->select(['d.ward_code', 'a.book_id', 'book_date', 'a.created_at', 'a.admission_id','patient_mrn', 'patient_name', 'b.class_name', 'a.class_code','ward_name', 'c.patient_id', 'book_preadmission'])
					->leftJoin('ward_classes as b', 'b.class_code','=', 'a.class_code')
					->leftJoin('patients as c', 'c.patient_id','=', 'a.patient_id')
					->leftJoin('wards as d', 'd.ward_code', '=', 'a.ward_code')
					->leftJoin('admissions as e', 'e.admission_id','=', 'a.admission_id')
					->leftJoin('encounters as f', 'f.encounter_id', '=', 'e.encounter_id')
					->leftJoin('discharges as g', 'g.encounter_id', '=', 'f.encounter_id')
					->where('book_date','>=', Carbon::today())
					->orderBy('a.book_date');


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
					'bedHelper'=> new BedHelper(),
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
			$bed_booking->book_date = DojoUtility::today();
			$bed_booking->book_preadmission = $request->book_preadmission;


			$patient = Patient::find($patient_id);
			$encounter = $patient->activeEncounter();
			if (!empty($encounter->admission)) {
					$bed_booking->user_id = $encounter->admission->user_id;
					$bed_booking->ward_code = $encounter->admission->bed->ward_code;
					$bed_booking->class_code = $encounter->admission->bed->class_code;
					$bed_booking->admission_id = $encounter->admission->admission_id;
			}

			$title = "Bed Reservation";

			$wards = Ward::select(DB::raw("ward_name, ward_code"))
							->where('ward_omission', '=', '0')	
							->where('ward_code', '<>', 'mortuary')	
							->where('ward_code', '<>', 'observation')	
							->orderBy('ward_name')
							->lists('ward_name', 'ward_code')
							->prepend('','');

			$ward_classes = Bed::select(DB::raw('beds.ward_code, class_name, beds.class_code,beds.encounter_code,  count(*)'))
					->leftJoin('ward_classes as b', 'b.class_code', '=', 'beds.class_code')
					->groupBy(['ward_code','b.class_code'])
					->get();

			return view('bed_bookings.create', [
					'bed_booking' => $bed_booking,
					'ward' => $wards,
					'class' => WardClass::all()->sortBy('class_name')->lists('class_name', 'class_code')->prepend('',''),
					'patient' => Patient::find($bed_booking->patient_id),
					'title' => $title,
					'admission_id' => $admission_id,
					'consultants' => $this->getConsultants(),
					'priority' => Priority::all()->sortBy('priority_name')->lists('priority_name', 'priority_code')->prepend('',''),
					'book' => $request->book,
					'ward_classes' => $ward_classes,
					]);
	}

	public function store(Request $request) 
	{
			$bed_booking = new BedBooking();

			$book_date = DojoUtility::dateWriteFormat($request->book_date);
			$count = BedBooking::where('book_date', $book_date)
						->where('ward_code', $request->ward_code)
						->count();

			$title = "Bed Reservation";
			if ($request->book=='preadmission') $title = "Preadmission";

			$limit = Config::get('host.reservation_limit');
			if ($count>=$limit) {
					$valid=null;
					$valid['book_date']='Booking limit reached. Please choose a new date.';
					Session(['title'=>$title]);
					return redirect('/bed_bookings/create/'.$request->patient_id.'/'.$request->admission_id)
							->withErrors($valid)
							->withInput();
			}
			
			$bed_booking = new BedBooking($request->all());
			$valid = $bed_booking->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$bed_booking = new BedBooking($request->all());
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

			$ward_classes = Bed::select(DB::raw('beds.ward_code, class_name, beds.class_code,beds.encounter_code,  count(*)'))
					->leftJoin('ward_classes as b', 'b.class_code', '=', 'beds.class_code')
					->groupBy(['ward_code','b.class_code'])
					->get();

			return view('bed_bookings.edit', [
					'bed_booking'=>$bed_booking,
					'patient' => Patient::find($bed_booking->patient_id),
					'class' => WardClass::all()->sortBy('class_name')->lists('class_name', 'class_code')->prepend('',''),
					'ward' => Ward::where('ward_code','<>','mortuary')->orderBy('ward_name')->lists('ward_name', 'ward_code')->prepend('',''),
					'admission_id' => $bed_booking->admission_id,
					'consultants' => $this->getConsultants(),
					'priority' => Priority::all()->sortBy('priority_name')->lists('priority_name', 'priority_code')->prepend('',''),
					'book' => null,
					'ward_classes' => $ward_classes,
					]);
	}

	public function update(Request $request, $id) 
	{
			$bed_booking = BedBooking::findOrFail($id);
			$bed_booking->fill($request->input());

			$bed_booking->book_preadmission = $request->book_preadmission ?: 0;

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
			return redirect('/bed_bookings?type=preadmission');
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

	public function enquiry(Request $request)
	{
			$date_start = DojoUtility::dateWriteFormat($request->date_start);
			$date_end = DojoUtility::dateWriteFormat($request->date_end);

			$bed_bookings = BedBooking::select('patient_name', 'patient_mrn', 'book_date', 'ward_name', 'class_name', 'priority_name')
					->leftJoin('patients as b', 'b.patient_id', '=', 'bed_bookings.patient_id')
					->leftJoin('beds as c', 'c.bed_code', '=', 'bed_bookings.bed_code')
					->leftJoin('wards as d', 'd.ward_code', '=', 'bed_bookings.ward_code')
					->leftJoin('ward_classes as e', 'e.class_code', '=', 'bed_bookings.class_code')
					->leftJoin('ref_priorities as f', 'f.priority_code', '=', 'bed_bookings.priority_code')
					->orderBy('book_id');

			if (!empty($request->search)) {
					$bed_bookings = $bed_bookings->where('patient_name','like','%'.$request->search.'%')
							->orWhere('patient_mrn', 'like','%'.$request->search.'%');
			}

			if (!empty($request->date_start) && empty($request->date_end)) {
				$bed_bookings = $bed_bookings->where('book_date', '>=', $date_start.' 00:00');
			}

			if (empty($request->date_start) && !empty($request->date_end)) {
				$bed_bookings = $bed_bookings->where('book_date', '<=', $date_end.' 23:59');
			}

			if (!empty($request->date_start) && !empty($request->date_end)) {
				$bed_bookings = $bed_bookings->whereBetween('book_date', array($date_start.' 00:00', $date_end.' 23:59'));
			} 

			if (!empty($request->ward_code)) {
					$bed_bookings = $bed_bookings->where('bed_bookings.ward_code','=',$request->ward_code);
			}

			if (!empty($request->class_code)) {
					$bed_bookings = $bed_bookings->where('bed_bookings.class_code','=',$request->class_code);
			}

			if (!empty($request->user_id)) {
					$bed_bookings = $bed_bookings->where('user_id','=',$request->user_id);
			}

			if ($request->export_report) {
					DojoUtility::export_report($bed_bookings->get());
			}

			$bed_bookings = $bed_bookings->paginate($this->paginateValue);

			return view('bed_bookings.enquiry', [
					'bed_bookings'=>$bed_bookings,
					'search'=>$request->search,
					'date_start'=>$date_start,
					'date_end'=>$date_end,
					'ward' => Ward::where('ward_code','<>','mortuary')->orderBy('ward_name')->lists('ward_name', 'ward_code')->prepend('',''),
					'class' => WardClass::all()->sortBy('class_name')->lists('class_name', 'class_code')->prepend('',''),
					'consultants' => $this->getConsultants(),
					'ward_code' => $request->ward_code,
					'class_code' => $request->class_code,
					'user_id' => $request->user_id,
					]);
	}

	public function getConsultants()
	{
			$consultants = User::leftjoin('user_authorizations as a','a.author_id', '=', 'users.author_id')
							->where('consultant',1)
							->orderBy('name')
							->lists('name','id')
							->prepend('','');
			return $consultants;
	}
}
