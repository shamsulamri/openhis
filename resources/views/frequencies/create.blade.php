@extends('layouts.app')

@section('content')
<h1>
New Frequency
</h1>
@include('common.errors')
<br>
{{ Form::model($frequency, ['url'=>'frequencies', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('frequency_code')) has-error @endif'>
        <label for='frequency_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('frequency_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('frequency_code')) <p class="help-block">{{ $errors->first('frequency_code') }}</p> @endif
        </div>
    </div>    
    
	@include('frequencies.frequency')
{{ Form::close() }}

@endsection
