@extends('layouts.app')

@section('content')
@include('consultations.panel')


{{ Form::model($newborn, ['url'=>'newborns', 'class'=>'form-horizontal']) }} 
	@include('newborns.newborn')
{{ Form::close() }}

@endsection
