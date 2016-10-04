@extends('layouts.app')

@section('content')
<h1>
New Encounter Type
</h1>
@include('common.errors')
<br>
{{ Form::model($encounter_type, ['url'=>'encounter_types', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('encounter_code')) has-error @endif'>
        <label for='encounter_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('encounter_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('encounter_code')) <p class="help-block">{{ $errors->first('encounter_code') }}</p> @endif
        </div>
    </div>    
    
	@include('encounter_types.encounter_type')
{{ Form::close() }}

@endsection
