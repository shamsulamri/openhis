@extends('layouts.app')

@section('content')
@include('patients.id')
<h1>
Prints
</h1>

<br>
<h4>
		<span class='fa fa-file-o' aria-hidden='true'></span>
		<a target="_blank" href="{{ Config::get('host.report_server') }}/ReportServlet?report=patient_label&id={{ $patient->patient_id }}">
		Patient Label
		</a>

		<br><br>
		<span class='fa fa-file-o' aria-hidden='true'></span>
		<a target="_blank" href="{{ Config::get('host.report_server') }}/ReportServlet?report=patient_lab&id={{ $patient->patient_id }}">
		Patient Label (Compact)
		</a>

@if ($patient->activeEncounter()) 
		<br><br>
		<span class='fa fa-file-o' aria-hidden='true'></span>
		<a target="_blank" href="{{ Config::get('host.report_server') }}/ReportServlet?report=wrist_label&id={{ $patient->activeEncounter()->encounter_id }}">
		Patient Wrist Label
		</a>

		@if ($patient->patientAgeInDays()<730)
		<br><br>
		<span class='fa fa-file-o' aria-hidden='true'></span>
		<a target="_blank" href="{{ Config::get('host.report_server') }}/ReportServlet?report=wrist_newborn_patient&id={{ $patient->activeEncounter()->encounter_id }}">
		Patient Wrist Label (Newborn)
		</a>

		@endif
@endif


@if ($newborns) 
	@foreach($newborns as $newborn)
		<br><br>
		<span class='fa fa-file-o' aria-hidden='true'></span>
		<a target="_blank" href="{{ Config::get('host.report_server') }}/ReportServlet?report=wrist_newborn&id={{ $newborn->patient_id }}">
		Newborn Wrist Label
		</a>
	@endforeach
@endif
		<br><br>
		<span class='fa fa-file-o' aria-hidden='true'></span>
		<a target="_blank" href="{{ Config::get('host.report_server') }}/ReportServlet?report=patient_detail&id={{ $patient->patient_id }}">
		Patient Demography	
		</a>

		<!--
		<br><br>
		<span class='fa fa-file-o' aria-hidden='true'></span>
		<a target="_blank" href="/pdf/{{ $patient->patient_id }}/darah">
		Borang Persetujuan Pemindahan Darah Atau Komponen Darah
		</a>
		-->
</h4>

@endsection
