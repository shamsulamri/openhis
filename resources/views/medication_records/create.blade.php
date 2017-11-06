@extends('layouts.app')

@section('content')
<h1>
New Medication Record
</h1>
@include('common.errors')
<br>
{{ Form::model($medication_record, ['url'=>'medication_records', 'class'=>'form-horizontal']) }} 
    
	@include('medication_records.medication_record')
{{ Form::close() }}

@endsection
