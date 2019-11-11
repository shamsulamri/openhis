@extends('layouts.app')

@section('content')
@include('patients.id_only')
<h1>
Ward Discharge
</h1>

<br>
{{ Form::model($ward_discharge, ['url'=>'ward_discharges', 'class'=>'form-horizontal','onsubmit'=>'submitButton.disabled = true; return true;']) }} 
	@include('ward_discharges.ward_discharge')
{{ Form::close() }}

@endsection
