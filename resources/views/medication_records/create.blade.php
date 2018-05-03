@extends('layouts.app')

@section('content')
@include('patients.id_only')
<h1>
Medication Administration Record
</h1>
@include('common.errors')
<br>
{{ Form::model($medication_record, ['url'=>'medication_records', 'class'=>'form-horizontal']) }} 
    
	@include('medication_records.medication_record')
{{ Form::close() }}

@endsection
