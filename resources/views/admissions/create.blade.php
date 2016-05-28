@extends('layouts.app')

@section('content')
@include('patients.id')
@include('common.errors')

{{ Form::model($admission, ['url'=>'admissions', 'class'=>'form-horizontal']) }} 
    
	@include('admissions.admission')
{{ Form::close() }}

@endsection
