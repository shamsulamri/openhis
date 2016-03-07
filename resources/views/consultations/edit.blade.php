@extends('layouts.app')

@section('content')
@include('patients.label')
@include('consultations.panel')
@include('common.errors')
@if (Session::has('message'))
<br>
<div class="alert alert-info">{{ Session::get('message') }}</div>
@else 
<br>
@endif
{{ Form::model($consultation, ['route'=>['consultations.update',$consultation->consultation_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
@include('consultations.consultation')
{{ Form::close() }}
@endsection
