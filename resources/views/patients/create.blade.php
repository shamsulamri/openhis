@extends('layouts.app')

@section('content')
<h1>
New Patient
<div class='pull-right'>
			<img id='show_image' src='/profile-img.png' style='border:2px solid gray' height='90' width='75'>
			<br>
			<br>
</div>
</h1>
@include('common.errors')
<br>
{{ Form::model($patient, ['url'=>'patients', 'class'=>'form-horizontal','enctype'=>'multipart/form-data']) }}
	@include('patients.patient')
{{ Form::close() }}

@endsection
