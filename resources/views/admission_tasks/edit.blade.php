@extends('layouts.app')

@section('content')
@include('patients.id')
<h1>
Edit Task
</h1>
@include('common.errors')
<br>
{{ Form::model($admission_task, ['route'=>['admission_tasks.update',$admission_task->order_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('admission_tasks.admission_task')
{{ Form::close() }}

@endsection
