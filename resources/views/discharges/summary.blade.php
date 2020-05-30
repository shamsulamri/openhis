@extends('layouts.app')

@section('content')
@include('patients.id_only')

<h1>Discharge Summary</h1>
<br>
{{ Form::model($discharge, ['route'=>['discharges.update', $discharge->discharge_id],'method'=>'put', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group  @if ($errors->has('discharge_diagnosis')) has-error @endif'>
        {{ Form::label('discharge_diagnosis', 'Diagnosis',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::textarea('discharge_diagnosis', null, ['class'=>'form-control','placeholder'=>'','rows'=>'4']) }}
            @if ($errors->has('discharge_diagnosis')) <p class="help-block">{{ $errors->first('discharge_diagnosis') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('discharge_summary')) has-error @endif'>
        {{ Form::label('discharge_summary', 'Summary',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::textarea('discharge_summary', null, ['class'=>'form-control','placeholder'=>'','rows'=>'10']) }}
            @if ($errors->has('discharge_summary')) <p class="help-block">{{ $errors->first('discharge_summary') }}</p> @endif
        </div>
    </div>
    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
{{ Form::close() }}

@endsection
