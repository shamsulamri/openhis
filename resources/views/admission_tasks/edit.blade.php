@extends('layouts.app')

@section('content')
<h1>
Edit Admission Task
</h1>
@include('common.errors')
<br>
{{ Form::model($admission_task, ['route'=>['admission_tasks.update',$admission_task->order_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('admission_tasks.admission_task')
{{ Form::close() }}

@endsection
