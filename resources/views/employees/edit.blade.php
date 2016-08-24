@extends('layouts.app')

@section('content')
<h1>
View Employee
</h1>
@include('common.errors')
<br>
{{ Form::model($employee, ['route'=>['employees.update',$employee->empid],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group'>
        <div class="col-sm-12">
            <a class="btn btn-default" href="/employees" role="button">Return</a>
            <a class="btn btn-primary" href="/employees/make_user/{{ $employee->empid }}" role="button">Make User</a>
        </div>
    </div>

    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-2 control-label'>empid<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::label('empid', $employee->empid, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('employees.employee')
{{ Form::close() }}

@endsection
