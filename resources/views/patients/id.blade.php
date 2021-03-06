@include('patients.id_only')
@include('patients.notification')

<br>
<a class='btn btn-default' href='{{ URL::to('patients/'. $patient->patient_id . '/edit') }}'>
						<span class='glyphicon glyphicon-user' aria-hidden='true'></span><br>Demography
</a>
<a class='btn btn-default' href='{{ URL::to('patients/dependant_list/'. $patient->patient_id) }}'>
		<span class='fa fa-users' aria-hidden='true'></span>
		<br>
		Dependants
</a>
<a class='btn btn-default' href='{{ URL::to('appointment_services/'. $patient->patient_id . '/0') }}'>
						<span class='glyphicon glyphicon-calendar' aria-hidden='true'></span><br>Appointment
</a>
<a class='btn btn-default'  href="{{ URL::to('preadmissions/create/'. $patient->patient_id.'?book=preadmission') }}">
		<span class='glyphicon glyphicon-bed' aria-hidden='true'></span>
		<br>
		Reservation 
</a>
<!--
<a class='btn btn-default' href='{{ URL::to('loans/request/'. $patient->patient_mrn.'?type=folder') }}'>
	<span class='glyphicon glyphicon-folder-close' aria-hidden='true'></span>
	<br>
	&nbsp;&nbsp;Folder&nbsp;&nbsp;
</a>
-->

<a class='btn btn-default' href='{{ URL::to('patient/prints/'. $patient->patient_id) }}'>
	<span class='glyphicon glyphicon-print' aria-hidden='true'></span>
	<br>
	&nbsp;&nbsp;Prints&nbsp;&nbsp;
</a>

@can('module-discharge')
		<a class='btn btn-default'  href='{{ URL::to('deposits/index/'. $patient->patient_id) }}'>
				<span class='fa fa-money' aria-hidden='true'></span>
				<br>
				Deposit 
		</a>
@endcan
@can('module-discharge')
		<!--
		<a class='btn btn-default'  href='{{ URL::to('payments/'. $patient->patient_id) }}'>
				<span class='fa fa-money' aria-hidden='true'></span>
				<br>
				Payment 
		</a>
		-->
		<!--
		<a class='btn btn-default'  href='{{ URL::to('refund/transactions/'. $patient->patient_id) }}'>
				<span class='fa fa-money' aria-hidden='true'></span>
				<br>
				Refund 
		</a>
		-->
@endcan

@can('module-medical-record')
<a class='btn btn-default'  href="{{ URL::to('documents?patient_mrn='. $patient->patient_mrn.'&from=view') }}">
		<span class='glyphicon glyphicon-file' aria-hidden='true'></span>
		<br>
		Documents
</a>
<a class='btn btn-default'  href='{{ URL::to('patient/merge/'. $patient->patient_id) }}'>
<span class='glyphicon glyphicon-duplicate' aria-hidden='true'></span>
<br>
&nbsp;&nbsp;Merge&nbsp;&nbsp;
</a>
@endcan


@if ($patient->patient_block==0)
<a class='btn btn-primary pull-right @If ($patient->activeEncounter()) disabled @endif' href='{{ URL::to('encounters/create?patient_id='. $patient->patient_id) }}'>
<span class='fa fa-flag' aria-hidden='true'></span>
<br>
Encounter
</a>
@endif
