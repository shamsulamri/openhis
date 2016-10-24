@extends('layouts.app')

@section('content')
<h1>Reports</h1>
<br>
{{ Form::model($report, ['url'=>'reports', 'class'=>'form-horizontal']) }} 
    <div class='form-group  @if ($errors->has('report')) has-error @endif'>
        <label for='report' class='col-sm-3 control-label'>Report</label>
        <div class='col-sm-9'>
			{{ Form::select('report', $reports,null, ['class'=>'form-control','maxlength'=>'10']) }}
        </div>
    </div>

    <div class='form-group  @if ($errors->has('report')) has-error @endif'>
        <label for='report' class='col-sm-3 control-label'>Encounter</label>
        <div class='col-sm-9'>
			{{ Form::select('encounter', $encounters,null, ['class'=>'form-control','maxlength'=>'10']) }}
        </div>
    </div>

    <div class='form-group  @if ($errors->has('report')) has-error @endif'>
        <label for='report' class='col-sm-3 control-label'>Ward</label>
        <div class='col-sm-9'>
			{{ Form::select('ward_code', $wards,null, ['class'=>'form-control','maxlength'=>'10']) }}
        </div>
    </div>

    <div class='form-group  @if ($errors->has('report')) has-error @endif'>
        <label for='report' class='col-sm-3 control-label'>Service</label>
        <div class='col-sm-9'>
			{{ Form::select('service_id', $services,null, ['class'=>'form-control','maxlength'=>'10']) }}
        </div>
    </div>

    <div class='form-group  @if ($errors->has('report')) has-error @endif'>
        <label for='report' class='col-sm-3 control-label'>User</label>
        <div class='col-sm-9'>
			{{ Form::select('user', $user,null, ['class'=>'form-control','maxlength'=>'10']) }}
        </div>
    </div>

    <div class='form-group  @if ($errors->has('date_start')) has-error @endif'>
        <label for='date_start' class='col-sm-3 control-label'>Date Start</label>
        <div class='col-sm-9'>
			<input id="date_start" name="date_start" type="text">
        </div>
    </div>

    <div class='form-group  @if ($errors->has('date_end')) has-error @endif'>
        <label for='date_end' class='col-sm-3 control-label'>Date End</label>
        <div class='col-sm-9'>
			<input id="date_end" name="date_end" type="text">
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            {{ Form::submit('Submit', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
{{ Form::close() }}
	<script>
		$(function(){
				$('#date_start').combodate({
						format: "DD/MM/YYYY",
						template: "DD MMMM YYYY",
						value: '{{ $today }}',
						maxYear: '{{ $minYear+5 }}',
						minYear: '{{ $minYear }}',
						customClass: 'select'
				});    
		});

		$(function(){
				$('#date_end').combodate({
						format: "DD/MM/YYYY",
						template: "DD MMMM YYYY",
						value: '{{ $tomorrow }}',
						maxYear: '{{ $minYear+5 }}',
						minYear: '{{ $minYear }}',
						customClass: 'select'
				});    
		});
	</script>
@endsection
