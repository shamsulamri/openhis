@extends('layouts.app')

@section('content')
<h1>
New Ward Discharge
</h1>
@include('common.errors')
<br>
{{ Form::model($ward_discharge, ['url'=>'ward_discharges', 'class'=>'form-horizontal']) }} 
    
	@include('ward_discharges.ward_discharge')
{{ Form::close() }}

@endsection
