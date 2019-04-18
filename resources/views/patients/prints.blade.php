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
		<a target="_blank" href="{{ Config::get('host.report_server') }}/ReportServlet?report=wrist_label&id={{ $patient->patient_id }}">
		Wrist Label
		</a>

		<br><br>
		<span class='fa fa-file-o' aria-hidden='true'></span>
		<a target="_blank" href="/pdf/{{ $patient->patient_id }}/darah">
		Borang Persetujuan Pemindahan Darah Atau Komponen Darah
		</a>
</h4>

@endsection
