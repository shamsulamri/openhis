@extends('layouts.app')

@section('content')
@include('patients.id')
<h1>
Ward Discharge
</h1>

<br>
{{ Form::model($ward_discharge, ['url'=>'ward_discharges', 'class'=>'form-horizontal']) }} 
    
	@include('ward_discharges.ward_discharge')
{{ Form::close() }}

@endsection
