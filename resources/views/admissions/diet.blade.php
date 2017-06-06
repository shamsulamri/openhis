
@extends('layouts.app')

@section('content')
@include('consultations.panel')
<h1>Dietary</h1>
<br>


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

	<!--
    <div class='form-group  @if ($errors->has('class_code')) has-error @endif'>
        {{ Form::label('class_code', 'Class',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::select('class_code', $class,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('class_code')) <p class="help-block">{{ $errors->first('class_code') }}</p> @endif
        </div>
    </div>
	-->

   <div class='form-group  @if ($errors->has('diet_description')) has-error @endif'>
        <label for='diet_description' class='col-sm-3 control-label'>Description</label>
        <div class='col-sm-9'>
            {{ Form::textarea('diet_description', null, ['class'=>'form-control','rows'=>'4',]) }}
            @if ($errors->has('diet_description')) <p class="help-block">{{ $errors->first('diet_description') }}</p> @endif
        </div>
    </div>

	<hr>
<h4>Nil by Mouth</h4>
	<!--
    <div class='form-group  @if ($errors->has('nbm_status')) has-error @endif'>
        {{ Form::label('nbm_status', 'Nil by Mouth',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::checkbox('nbm_status', '1') }}
            @if ($errors->has('nbm_status')) <p class="help-block">{{ $errors->first('nbm_status') }}</p> @endif
        </div>
    </div>
	-->

	@if (!empty($admission->nbm_datetime))

	<div class="row">
			<div class="col-sm-6">
					<div class='form-group  @if ($errors->has('nbm_start_date')) has-error @endif'>
						<label for='nbm_start_date' class='col-sm-3 col-sm-offset-3 control-label'>Date Start</label>
						<div class='col-sm-6'>
								{{ Form::label('nbm_date',DojoUtility::dateDMYOnly($admission->nbm_datetime), ['class'=>'form-control']) }}
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('nbm_start_time')) has-error @endif'>
						<label for='nbm_start_time' class='col-sm-6 control-label'>Time Start</label>
						<div class='col-sm-6'>
								{{ Form::label('nbm_time',DojoUtility::timeReadFormat($admission->nbm_datetime), ['class'=>'form-control']) }}
						</div>
					</div>
			</div>
	</div>
	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('nbm_duration')) has-error @endif'>
						{{ Form::label('nbm_duration', 'Duration',['class'=>'col-md-6 control-label']) }}
						<div class='col-md-6'>
								{{ Form::label('duration',$admission->nbm_duration, ['class'=>'form-control']) }}
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('period_code')) has-error @endif'>
						{{ Form::label('unit', 'Period',['class'=>'col-md-6 control-label']) }}
						<div class='col-md-6'>
								{{ Form::label('duration',$admission->period->period_name, ['class'=>'form-control']) }}
						</div>
					</div>
			</div>
	</div>
	@else
	<div class="row">
			<div class="col-sm-6">
					<div class='form-group  @if ($errors->has('nbm_start_date')) has-error @endif'>
						<label for='nbm_start_date' class='col-sm-3 col-sm-offset-3 control-label'>Date Start</label>
						<div class='col-sm-6'>
							<div class="input-group date">
								<input data-mask="99/99/9999" name="nbm_start_date" id="nbm_start_date" type="text" class="form-control" value="{{ $nbm_start_date }}">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							</div>
							@if ($errors->has('nbm_start_date')) <p class="help-block">{{ $errors->first('nbm_start_date') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('nbm_start_time')) has-error @endif'>
						<label for='nbm_start_time' class='col-sm-6 control-label'>Time Start</label>
						<div class='col-sm-6'>
							<div id="nbm_start_time" name="nbm_start_time" class="input-group clockpicker" data-autoclose="true">
								<input name='nbm_start_time' type='text' class='form-control' value='{{ $nbm_start_time }}'>
								<span class="input-group-addon">
									<span class="fa fa-clock-o"></span>
								</span>
							</div>
							@if ($errors->has('nbm_start_time')) <p class="help-block">{{ $errors->first('nbm_start_time') }}</p> @endif
						</div>
					</div>
			</div>
	</div>
	<div class="row">
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('nbm_duration')) has-error @endif'>
						{{ Form::label('nbm_duration', 'Duration',['class'=>'col-md-6 control-label']) }}
						<div class='col-md-6'>
							{{ Form::text('nbm_duration', null, ['id'=>'duration','class'=>'form-control input-sm']) }}
							@if ($errors->has('nbm_duration')) <p class="help-block">{{ $errors->first('nbm_duration') }}</p> @endif
						</div>
					</div>
			</div>
			<div class="col-xs-6">
					<div class='form-group  @if ($errors->has('period_code')) has-error @endif'>
						{{ Form::label('unit', 'Period',['class'=>'col-md-6 control-label']) }}
						<div class='col-md-6'>
							{{ Form::select('period_code', $period,null, ['id'=>'period', 'class'=>'form-control input-sm']) }}
							@if ($errors->has('period_code')) <p class="help-block">{{ $errors->first('period_code') }}</p> @endif
						</div>
					</div>
			</div>
	</div>
	@endif

	@if (!empty($admission->nbm_datetime))
    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-warning" href="/nbm/remove/{{ $admission->admission_id }}" role="button">Clear Nil by Mouth</a>
        </div>
    </div>
	@endif
	<hr>
    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
			
	{{ Form::hidden('encounter_id', null) }}
	{{ Form::hidden('consultation_id', $consultation->consultation_id) }}

{{ Form::close() }}

	<script>
		$('#nbm_start_date').datepicker({
				format: "dd/mm/yyyy",
				todayBtn: "linked",
				keyboardNavigation: false,
				forceParse: false,
				calendarWeeks: true,
				autoclose: true
		});

		$('.clockpicker').clockpicker();
	</script>
@endsection
