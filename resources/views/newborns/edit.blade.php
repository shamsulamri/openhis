@extends('layouts.app')

@section('content')
@include('patients.label')
@include('common.errors')

{{ Form::model($newborn, ['route'=>['newborns.update',$newborn->newborn_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('newborns.newborn')
{{ Form::close() }}

@endsection
