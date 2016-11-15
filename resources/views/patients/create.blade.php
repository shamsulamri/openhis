@extends('layouts.app')

@section('content')
<h1>
New Patient
<!--
<div class='pull-right'>
			<img id='show_image' src='/profile-img.png' style='border:2px solid gray' height='60' width='55'>
			<br>
			<br>
</div>
-->
</h1>

{{ Form::model($patient, ['url'=>'patients', 'class'=>'form-horizontal','enctype'=>'multipart/form-data']) }}
	@include('patients.patient')
{{ Form::close() }}

@endsection
