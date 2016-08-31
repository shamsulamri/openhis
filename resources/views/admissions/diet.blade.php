
@extends('layouts.app')

@section('content')
@include('consultations.panel')
<h1>Dietary</h1>
<br>
@include('common.errors')
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
{{ Form::model($admission, ['route'=>['admissions.update',$admission->admission_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 

    <div class='form-group  @if ($errors->has('diet_code')) has-error @endif'>
        {{ Form::label('diet_code', 'Diet',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::select('diet_code', $diet,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('diet_code')) <p class="help-block">{{ $errors->first('diet_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('texture_code')) has-error @endif'>
        {{ Form::label('texture_code', 'Texture',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::select('texture_code', $texture,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('texture_code')) <p class="help-block">{{ $errors->first('texture_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('class_code')) has-error @endif'>
        {{ Form::label('class_code', 'Class',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::select('class_code', $class,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('class_code')) <p class="help-block">{{ $errors->first('class_code') }}</p> @endif
        </div>
    </div>

   <div class='form-group  @if ($errors->has('diet_description')) has-error @endif'>
        <label for='diet_description' class='col-sm-3 control-label'>Description</label>
        <div class='col-sm-9'>
            {{ Form::textarea('diet_description', null, ['class'=>'form-control','rows'=>'4',]) }}
            @if ($errors->has('diet_description')) <p class="help-block">{{ $errors->first('diet_description') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('admission_nbm')) has-error @endif'>
        {{ Form::label('admission_nbm', 'Nil by Mouth',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::checkbox('admission_nbm', '1') }}
            @if ($errors->has('admission_nbm')) <p class="help-block">{{ $errors->first('admission_nbm') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
			
	{{ Form::hidden('encounter_id', null) }}
	{{ Form::hidden('consultation_id', $consultation->consultation_id) }}

{{ Form::close() }}

@endsection
