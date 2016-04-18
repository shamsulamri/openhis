@extends('layouts.app')

@section('content')
<h1>
New Patient
</h1>
@include('common.errors')
<br>
{{ Form::model($patient, ['url'=>'patients', 'class'=>'form-horizontal']) }}
	@include('patients.patient')
{{ Form::close() }}

@endsection
