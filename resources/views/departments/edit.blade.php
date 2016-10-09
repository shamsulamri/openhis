@extends('layouts.app')

@section('content')
<h1>
Edit Department
</h1>
@include('common.errors')
<br>
{{ Form::model($department, ['route'=>['departments.update',$department->department_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('department_code')) has-error @endif'>
        <label for='department_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::label('department_code', $department->department_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('departments.department')
{{ Form::close() }}

@endsection
