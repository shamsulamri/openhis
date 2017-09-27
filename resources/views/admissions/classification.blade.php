
@extends('layouts.app')

@section('content')
@include('consultations.panel')
<h1>Patient Classification Index</h1>
<br>


<form id='form' action='/admission/classification' method='post' class='form-horizontal'>

    <div class='form-group  @if ($errors->has('classification_code')) has-error @endif'>
        {{ Form::label('classification_code', 'Classification Index',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('classification_code', $classification,$admission->classification_code, ['id'=>'classification_code', 'class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('classification_code')) <p class="help-block">{{ $errors->first('classification_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-9">
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
			
	{{ Form::hidden('encounter_id', null) }}
	{{ Form::hidden('consultation_id', $consultation->consultation_id) }}
	{{ Form::hidden('admission_id', $admission->admission_id) }}
	<input type='hidden' name="_token" value="{{ csrf_token() }}">

{{ Form::close() }}
@endsection
