@extends('layouts.app')

@section('content')
@include('patients.id_only')

<h1>Discharge Summary</h1>
<a class='btn btn-danger pull-right' href='/discharge_summary/reset/{{ $summary->encounter_id }}'>Reset</a>
<br>
<br>
<br>
{{ Form::model($summary, ['route'=>['discharge_summaries.update', $summary->encounter_id],'method'=>'put', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group  @if ($errors->has('summary_diagnosis')) has-error @endif'>
        {{ Form::label('summary_diagnosis', 'Discharge Diagnosis',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::textarea('summary_diagnosis', null, ['class'=>'form-control','placeholder'=>'','rows'=>'4']) }}
            @if ($errors->has('summary_diagnosis')) <p class="help-block">{{ $errors->first('summary_diagnosis') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('summary_treatment')) has-error @endif'>
        {{ Form::label('summary_treatment', 'Treatment/Investigation',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::textarea('summary_treatment', null, ['class'=>'form-control','placeholder'=>'','rows'=>'4']) }}
            @if ($errors->has('summary_treatment')) <p class="help-block">{{ $errors->first('summary_treatment') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('summary_surgical')) has-error @endif'>
        {{ Form::label('summary_surgical', 'Operation/Surgery',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::textarea('summary_surgical', null, ['class'=>'form-control','placeholder'=>'','rows'=>'4']) }}
            @if ($errors->has('summary_surgical')) <p class="help-block">{{ $errors->first('summary_surgical') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('summary_follow_up')) has-error @endif'>
        {{ Form::label('summary_follow_up', 'Follow-up',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::textarea('summary_follow_up', null, ['class'=>'form-control','placeholder'=>'','rows'=>'4']) }}
            @if ($errors->has('summary_follow_up')) <p class="help-block">{{ $errors->first('summary_follow_up') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('summary_medication')) has-error @endif'>
        {{ Form::label('summary_medication', 'Medication Before Discharge',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::textarea('summary_medication', null, ['class'=>'form-control','placeholder'=>'','rows'=>'4']) }}
            @if ($errors->has('summary_medication')) <p class="help-block">{{ $errors->first('summary_medication') }}</p> @endif
        </div>
    </div>
    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
{{ Form::close() }}

@endsection
