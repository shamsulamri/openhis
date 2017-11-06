@extends('layouts.app')

@section('content')
<h1>
Edit Medication Record
</h1>
@include('common.errors')
<br>
{{ Form::model($medication_record, ['route'=>['medication_records.update',$medication_record->medication_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('medication_records.medication_record')
{{ Form::close() }}

@endsection
