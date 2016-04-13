<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\BedBooking;
use Log;
use DB;
use Session;
use App\Admission;
use App\WardClass;
use App\Patient;
use App\Bed;

class BedBookingController extends Controller
{
	public $paginateValue=10;

	public function __construct()
	{
			$this->middleware('auth');
	}

	public function index()
	{
			$sql = '
			select a.class_code, (count(*)-IFNULL(occupied,0)) as available from beds a
			left join (
						select count(*) occupied, b.class_code 
						from admissions as a
						left join beds as b on (a.bed_code = b.bed_code)
						where b.class_code is not null
						group by class_code
			) as b on (a.class_code = b.class_code)
			group by a.class_code
			';
			$beds = DB::select($sql);
			$class_availability = [];
			foreach ($beds as $bed) {
					$class_availability[$bed->class_code]=$bed->available;
			}

			//return $class_availability;
			$bed_bookings = DB::table('bed_bookings as a')
					->select(['book_id', 'a.created_at', 'patient_name', 'b.class_name', 'a.class_code'])
					->leftJoin('ward_classes as b', 'b.class_code','=', 'a.class_code')
					->leftJoin('patients as c', 'c.patient_id','=', 'a.patient_id')
					->orderBy('a.created_at')
					->paginate($this->paginateValue);
		
			return view('bed_bookings.index', [
					'bed_bookings'=>$bed_bookings,
					'class_availability' => $class_availability,
			]);
	}

	public function create($patient_id, $admission_id = null)
	{
			$bed_booking = new BedBooking();
			$bed_booking->patient_id = $patient_id;
			$bed_booking->book_date = date('d/m/Y');

			if ($admission_id != null) {
					$admission = Admission::find($admission_id);
					$bed_booking->bed_code = $admission->bed_code;
			}
			return view('bed_bookings.create', [
					'bed_booking' => $bed_booking,
					'bed' => Bed::all()->sortBy('bed_name')->lists('bed_name', 'bed_code')->prepend('',''),
					'class' => WardClass::all()->sortBy('class_name')->lists('class_name', 'class_code')->prepend('',''),
					'patient' => Patient::find($bed_booking->patient_id),
					]);
	}

	public function store(Request $request) 
	{
			$bed_booking = new BedBooking();
			$valid = $bed_booking->validate($request->all(), $request->_method);

			if ($valid->passes()) {
					$bed_booking = new BedBooking($request->all());
					$bed_booking->book_id = $request->book_id;
					$bed_booking->save();
					Session::flash('message', 'Record successfully created.');
					return redirect('/bed_bookings');
			} else {
					return redirect('/bed_bookings/create')
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
		return view('bed_bookings.destroy', [
			'bed_booking'=>$bed_booking
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
