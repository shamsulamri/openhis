@if (!empty($patient))
<div class="row border-bottom gray-bg">
			<div class='col-sm-10'>
						<h2>{{ $patient->getTitle() }} {{ $patient->patient_name }}</h2>
						<h6>{{ $patient->patient_mrn }}</h6>
						<h6>{{ $patient->patientAge() }}</h6>
						@if ($patient->outstandingBill() < 0) 
						<h4>
						<p class='text-warning'>
						<strong>Warning !</strong> Outstanding bill
						</p>
						</h4>
						@endif
						@if (!empty($encounter->discharge))
								@if (!$encounter->encounterPaid())
								<h4 class='text-danger'>
									<strong>
									Encounter not paid
									</strong>	
								</h4>
								@endif
						@endif
			</div>
			<div class='col-md-2' align='right'>
					<h2>
					@if (Storage::disk('local')->has('/'.$patient->patient_mrn.'/'.$patient->patient_mrn))	
					<img id='show_image' src='{{ route('patient.image', ['id'=>$patient->patient_mrn]) }}' style='border:2px solid gray' height='80' width='70'>
					@else
							<img id='show_image' src='/profile-img.png' style='border:2px solid gray' height='80' width='70'>
					@endif
					</h2>
			</div>
			&nbsp;
</div>
<br>
<a class='btn btn-default' href='{{ URL::to('patients/'. $patient->patient_id . '/edit') }}'>
						<span class='glyphicon glyphicon-user' aria-hidden='true'></span><br>Demography
</a>
<a class='btn btn-default' href='{{ URL::to('patients/dependant_list/'. $patient->patient_id) }}'>
		<span class='fa fa-users' aria-hidden='true'></span>
		<br>
		Dependants
</a>
@can('module-patient')
<a class='btn btn-default' href='{{ URL::to('appointment_services/'. $patient->patient_id . '/0') }}'>
						<span class='glyphicon glyphicon-calendar' aria-hidden='true'></span><br>Appointment
</a>
<a class='btn btn-default'  href='{{ URL::to('preadmissions/create/'. $patient->patient_id.'?book=preadmission') }}'>
		<span class='glyphicon glyphicon-bed' aria-hidden='true'></span>
		<br>
		Preadmission 
</a>
@endcan
@can('module-discharge')
<a class='btn btn-default'  href='{{ URL::to('payments/'. $patient->patient_id) }}'>
		<span class='glyphicon glyphicon-book' aria-hidden='true'></span>
		<br>
		Payment 
</a>
@endcan
@can('module-medical-record')
<a class='btn btn-default'  href='{{ URL::to('documents?patient_mrn='. $patient->patient_mrn.'&from=view') }}'>
		<span class='glyphicon glyphicon-duplicate' aria-hidden='true'></span>
		<br>
	Medical Record 
</a>
@endcan
<a class='btn btn-default'  href='{{ URL::to('loans/request/'. $patient->patient_mrn.'?type=folder') }}'>
<span class='glyphicon glyphicon-folder-close' aria-hidden='true'></span>
<br>
	&nbsp;&nbsp;Folder&nbsp;&nbsp;
</a>
<a class='btn btn-default'  href='{{ Config::get('host.report_server') }}/ReportServlet?report=patient_label&id={{ $patient->patient_id }}'>
<span class='glyphicon glyphicon-print' aria-hidden='true'></span>
<br>
&nbsp;&nbsp;Label&nbsp;&nbsp;
</a>
<a class='btn btn-primary pull-right' href='{{ URL::to('encounters/create?patient_id='. $patient->patient_id) }}'>
<span class='fa fa-stethoscope' aria-hidden='true'></span>
<br>
Encounter
</a>
@endif
