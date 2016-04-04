
@extends('layouts.app')

@section('content')
@include('patients.label')
<h2>Dietary</h2>
<br>
@include('common.errors')
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
{{ Form::model($admission, ['route'=>['admissions.update',$admission->admission_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 

    <div class='form-group  @if ($errors->has('diet_code')) has-error @endif'>
        {{ Form::label('diet_code', 'Diet',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('diet_code', $diet,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('diet_code')) <p class="help-block">{{ $errors->first('diet_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('texture_code')) has-error @endif'>
        {{ Form::label('texture_code', 'Texture',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('texture_code', $texture,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('texture_code')) <p class="help-block">{{ $errors->first('texture_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('class_code')) has-error @endif'>
        {{ Form::label('class_code', 'Class',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('class_code', $class,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('class_code')) <p class="help-block">{{ $errors->first('class_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('admission_nbm')) has-error @endif'>
        {{ Form::label('admission_nbm', 'Nil by Mouth',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::checkbox('admission_nbm', '1') }}
            @if ($errors->has('admission_nbm')) <p class="help-block">{{ $errors->first('admission_nbm') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
			
	{{ Form::hidden('encounter_id', null) }}
	{{ Form::hidden('consultation_id', $consultation->consultation_id) }}

{{ Form::close() }}

@endsection