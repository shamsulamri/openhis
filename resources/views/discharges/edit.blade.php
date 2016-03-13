@extends('layouts.app')

@section('content')
<h1>
Edit Discharge
</h1>
@include('common.errors')
<br>
{{ Form::model($discharge, ['route'=>['discharges.update',$discharge->discharge_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('discharges.discharge')
{{ Form::close() }}

@endsection
