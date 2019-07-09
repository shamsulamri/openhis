@extends('layouts.app')

@section('content')
<h1>
Edit Fee Schedule
</h1>
@include('common.errors')
<br>
{{ Form::model($fee_schedule, ['route'=>['fee_schedules.update',$fee_schedule->fee_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-2 control-label'>fee_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::label('fee_code', $fee_schedule->fee_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('fee_schedules.fee_schedule')
{{ Form::close() }}

@endsection
