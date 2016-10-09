@extends('layouts.app')

@section('content')
<h1>
New Unit Measure
</h1>
@include('common.errors')
<br>
{{ Form::model($unit_measure, ['url'=>'unit_measures', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('unit_code')) has-error @endif'>
        <label for='unit_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('unit_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'10.0']) }}
            @if ($errors->has('unit_code')) <p class="help-block">{{ $errors->first('unit_code') }}</p> @endif
        </div>
    </div>    
    
	@include('unit_measures.unit_measure')
{{ Form::close() }}

@endsection
