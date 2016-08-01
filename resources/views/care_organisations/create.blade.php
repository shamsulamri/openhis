@extends('layouts.app')

@section('content')
<h1>
New Care Organisation
</h1>
@include('common.errors')
<br>
{{ Form::model($care_organisation, ['url'=>'care_organisations', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('organisation_code')) has-error @endif'>
        <label for='organisation_code' class='col-sm-3 control-label'>organisation_code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('organisation_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('organisation_code')) <p class="help-block">{{ $errors->first('organisation_code') }}</p> @endif
        </div>
    </div>    
    
	@include('care_organisations.care_organisation')
{{ Form::close() }}

@endsection
