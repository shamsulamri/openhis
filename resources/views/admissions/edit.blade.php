@extends('layouts.app')

@section('content')
@include('patients.id')
@include('common.errors')
{{ Form::model($admission, ['route'=>['admissions.update',$admission->admission_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('admissions.admission')
{{ Form::close() }}

@endsection
