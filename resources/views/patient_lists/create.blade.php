@extends('layouts.app')

@section('content')
<h1>
New Patient List
</h1>
@include('common.errors')
<br>
{{ Form::model($patient_list, ['url'=>'patient_lists', 'class'=>'form-horizontal']) }} 
    
	@include('patient_lists.patient_list')
{{ Form::close() }}

@endsection
