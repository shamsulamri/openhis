@extends('layouts.app')

@section('content')
<h1>
New Employee
</h1>
@include('common.errors')
<br>
{{ Form::model($employee, ['url'=>'employees', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('empid')) has-error @endif'>
        <label for='empid' class='col-sm-2 control-label'>empid<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('empid', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'0']) }}
            @if ($errors->has('empid')) <p class="help-block">{{ $errors->first('empid') }}</p> @endif
        </div>
    </div>    
    
	@include('employees.employee')
{{ Form::close() }}

@endsection
