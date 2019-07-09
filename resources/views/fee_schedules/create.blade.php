@extends('layouts.app')

@section('content')
<h1>
New Fee Schedule
</h1>
@include('common.errors')
<br>
{{ Form::model($fee_schedule, ['url'=>'fee_schedules', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('fee_code')) has-error @endif'>
        <label for='fee_code' class='col-sm-2 control-label'>fee_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('fee_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('fee_code')) <p class="help-block">{{ $errors->first('fee_code') }}</p> @endif
        </div>
    </div>    
    
	@include('fee_schedules.fee_schedule')
{{ Form::close() }}

@endsection
