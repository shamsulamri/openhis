@extends('layouts.app')

@section('content')
@include('consultations.panel')
@include('common.errors')

{{ Form::model($newborn, ['url'=>'newborns', 'class'=>'form-horizontal']) }} 
	@include('newborns.newborn')
{{ Form::close() }}

@endsection
