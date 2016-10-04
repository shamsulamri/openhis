@extends('layouts.app')

@section('content')
@include('patients.id')
<style>
table, th, td {
   	border: none;
	border-bottom: none !important;
}
</style>
<h1>
@if ($moves==1)
Admission Complete
@else
Move Complete
@endif
</h1>
@include('common.errors')
<br>
<h4>
Patient has been successfully admitted to the following location:
<br>
<br>
<table>
	<tr>
		<td width='100' height='30'>Ward</td>
		<td><strong>{{ $admission->bed->ward->ward_name }}</strong></td>
	</tr>
	@if ($admission->bed->wardClass)
	<tr>
		<td width='100' height='30'>Room</td>
		<td><strong>{{ $admission->bed->wardClass->class_name }}</strong></td>
	</tr>
	@endif
	<tr>
		<td width='100' height='30'>Bed</td>
		<td><strong>{{ $admission->bed->bed_name }}</strong></td>
	</tr>
</table>
<br>
<br>
<a class="btn btn-default" href="/admissions" role="button">Return</a>
</h4>
@endsection
