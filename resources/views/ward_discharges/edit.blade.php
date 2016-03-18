@extends('layouts.app')

@section('content')
<h1>
Edit Ward Discharge
</h1>
@include('common.errors')
<br>
{{ Form::model($ward_discharge, ['route'=>['ward_discharges.update',$ward_discharge->discharge_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('ward_discharges.ward_discharge')
{{ Form::close() }}

@endsection
