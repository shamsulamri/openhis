@extends('layouts.app')

@section('content')
@include('consultations.panel')
@include('common.errors')
<h1>Clincal Notes</h1>
<br>
@if (Session::has('message'))
<br>
<div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
{{ Form::model($consultation, ['route'=>['consultations.update',$consultation->consultation_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
@include('consultations.consultation')
{{ Form::close() }}
@endsection
