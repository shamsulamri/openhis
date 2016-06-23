@extends('layouts.app')

@section('content')
@include('patients.id')
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
The patient has been successfully admitted to the following location:
<br>
<br>
<table>
	<tr>
		<td width='100' height='30'>Ward</td>
		<td><strong>{{ $admission->bed->ward->ward_name }}</strong></td>
	</tr>
	<tr>
		<td width='100' height='30'>Room</td>
		<td><strong>{{ $admission->bed->room->room_name }}</strong></td>
	</tr>
	<tr>
		<td width='100' height='30'>Bed</td>
		<td><strong>{{ $admission->bed->bed_name }}</strong></td>
	</tr>
</table>
<br>
<br>
@can('module-patient')
<a class="btn btn-default" href="/patients" role="button">Return</a>
@endcan

@can('module-ward')
<a class="btn btn-default" href="/admissions" role="button">Return</a>
@endcan
</h4>
@endsection
