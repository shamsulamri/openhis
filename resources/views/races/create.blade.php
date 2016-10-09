@extends('layouts.app')

@section('content')
<h1>
New Race
</h1>
@include('common.errors')
<br>
{{ Form::model($race, ['url'=>'races', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('race_code')) has-error @endif'>
        <label for='race_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('race_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('race_code')) <p class="help-block">{{ $errors->first('race_code') }}</p> @endif
        </div>
    </div>    
    
	@include('races.race')
{{ Form::close() }}

@endsection
