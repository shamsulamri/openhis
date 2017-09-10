@extends('layouts.app')

@section('content')
<h1>
Edit Priority
</h1>
@include('common.errors')
<br>
{{ Form::model($priority, ['route'=>['priorities.update',$priority->priority_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-2 control-label'>priority_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::label('priority_code', $priority->priority_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('priorities.priority')
{{ Form::close() }}

@endsection
