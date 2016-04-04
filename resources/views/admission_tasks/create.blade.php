@extends('layouts.app')

@section('content')
<h1>
New Admission Task
</h1>
@include('common.errors')
<br>
{{ Form::model($admission_task, ['url'=>'admission_tasks', 'class'=>'form-horizontal']) }} 
    
	@include('admission_tasks.admission_task')
{{ Form::close() }}

@endsection
