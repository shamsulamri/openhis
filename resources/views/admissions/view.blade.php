@extends('layouts.app')

@section('content')
@include('patients.id')

<h1>Forms</h1>
<table class='table table-hover'>
@foreach($forms as $form)
	<tr>
	<td width='10'>
			<a class='btn btn-primary btn-xs' href='{{ URL::to('form/'. $form->form_code.'/'.$patient->patient_id.'/create') }}'>
				<span class='glyphicon glyphicon-plus'></span>
			</a>
	</td>
	<td width='10'>
	@if ($form->result_count)
			<a class='btn btn-success btn-xs pull-right' href='{{ URL::to('form/'. $form->form_code.'/'.$admission->encounter_id) }}'>View</a>
	@endif
	</td>
	<td>
				{{ $form->form_name }}
	</td>
	</tr>
@endforeach
</table>

@endsection
