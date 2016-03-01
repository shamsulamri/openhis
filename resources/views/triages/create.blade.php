@extends('layouts.app')

@section('content')
<h1>
New Triage
</h1>
@include('common.errors')
<br>
{{ Form::model($triage, ['url'=>'triages', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('triage_code')) has-error @endif'>
        <label for='triage_code' class='col-sm-2 control-label'>triage_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('triage_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'10.0']) }}
            @if ($errors->has('triage_code')) <p class="help-block">{{ $errors->first('triage_code') }}</p> @endif
        </div>
    </div>    
    
	@include('triages.triage')
{{ Form::close() }}

@endsection
