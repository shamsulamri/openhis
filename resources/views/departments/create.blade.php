@extends('layouts.app')

@section('content')
<h1>
New Department
</h1>
@include('common.errors')
<br>
{{ Form::model($department, ['url'=>'departments', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('department_code')) has-error @endif'>
        <label for='department_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('department_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('department_code')) <p class="help-block">{{ $errors->first('department_code') }}</p> @endif
        </div>
    </div>    
    
	@include('departments.department')
{{ Form::close() }}

@endsection
