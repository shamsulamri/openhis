@extends('layouts.app')

@section('content')
<h1>
New Diet Period
</h1>
@include('common.errors')
<br>
{{ Form::model($diet_period, ['url'=>'diet_periods', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('period_code')) has-error @endif'>
        <label for='period_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('period_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20.0']) }}
            @if ($errors->has('period_code')) <p class="help-block">{{ $errors->first('period_code') }}</p> @endif
        </div>
    </div>    
    
	@include('diet_periods.diet_period')
{{ Form::close() }}

@endsection
