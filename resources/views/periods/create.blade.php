@extends('layouts.app')

@section('content')
<h1>
New Period
</h1>
@include('common.errors')
<br>
{{ Form::model($period, ['url'=>'periods', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('period_code')) has-error @endif'>
        <label for='period_code' class='col-sm-2 control-label'>period_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('period_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'10.0']) }}
            @if ($errors->has('period_code')) <p class="help-block">{{ $errors->first('period_code') }}</p> @endif
        </div>
    </div>    
    
	@include('periods.period')
{{ Form::close() }}

@endsection
