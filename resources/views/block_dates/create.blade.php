@extends('layouts.app')

@section('content')
<h1>
New Block Date
</h1>
@include('common.errors')
<br>
{{ Form::model($block_date, ['url'=>'block_dates', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('block_code')) has-error @endif'>
        <label for='block_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('block_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20.0']) }}
            @if ($errors->has('block_code')) <p class="help-block">{{ $errors->first('block_code') }}</p> @endif
        </div>
    </div>    
    
	@include('block_dates.block_date')
{{ Form::close() }}

@endsection
