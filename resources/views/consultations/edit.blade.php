@extends('layouts.app')

@section('content')
@include('consultations.panel')
<h1>Clinical Notes</h1>
@include('common.errors')
{{ Form::model($consultation, ['route'=>['consultations.update',$consultation->consultation_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
@include('consultations.consultation')
{{ Form::close() }}
@endsection
