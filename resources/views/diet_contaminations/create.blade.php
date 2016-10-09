@extends('layouts.app')

@section('content')
<h1>
New Diet Contamination
</h1>
@include('common.errors')
<br>
{{ Form::model($diet_contamination, ['url'=>'diet_contaminations', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('contamination_code')) has-error @endif'>
        <label for='contamination_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('contamination_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('contamination_code')) <p class="help-block">{{ $errors->first('contamination_code') }}</p> @endif
        </div>
    </div>    
    
	@include('diet_contaminations.diet_contamination')
{{ Form::close() }}

@endsection
