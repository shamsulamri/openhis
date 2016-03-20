@extends('layouts.app')

@section('content')
<h1>
Edit Patient List
</h1>
@include('common.errors')
<br>
{{ Form::model($patient_list, ['route'=>['patient_lists.update',$patient_list->queue_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('patient_lists.patient_list')
{{ Form::close() }}

@endsection
