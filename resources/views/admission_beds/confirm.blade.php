@extends('layouts.app')

@section('content')
@include('patients.id_only')

<h1>
Bed Movement
</h1>
<br>
<h3>
Are you sure you want to move the patient ?
<br>
<br>
<br>
<table>
	<tr>
		<td width='100' height='30'>Ward</td>
		<td><strong>{{ $admission->bed->ward->ward_name }}</strong></td>
		<td><strong></strong></td>
		<td><strong>{{ $bed->ward->ward_name }}</strong></td>
	</tr>
	@if ($admission->bed->wardClass)
	<tr>
		<td width='100' height='30'>Class</td>
		<td><strong>{{ $admission->bed->wardClass->class_name }}</strong></td>
		<td width='200'><div align='center'>→</div></td>
		<td><strong>{{ $bed->wardClass->class_name }}</strong></td>
	</tr>
	@endif
	<tr>
		<td width='100' height='30'>Bed</td>
		<td><strong>{{ $admission->bed->bed_name }}</strong></td>
		<td><strong></strong></td>
		<td><strong>{{ $bed->bed_name }}</strong></td>
	</tr>
</table>
<br>
<br>
@if (empty($book_id)) 
{{ Form::open(['url'=>'admission_beds/move/'.$admission->admission_id.'/'.$bed_code, 'class'=>'pull-right']) }}
@else
{{ Form::open(['url'=>'admission_beds/move/'.$admission->admission_id.'/'.$bed_code.'?book_id='.$book_id, 'class'=>'pull-right']) }}
@endif
	<a class="btn btn-default" href="/admission_beds?flag=1&admission_id={{ $admission->admission_id }}" role="button">Cancel</a>
	{{ Form::submit('Yes', ['class'=>'btn btn-primary']) }}
{{ Form::close() }}

</h3>
@endsection
