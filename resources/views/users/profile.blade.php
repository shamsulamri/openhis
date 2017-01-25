@extends('layouts.app')

@section('content')
<h1>
User Profile
</h1>
<br>

@include('common.notification')
{{ Form::model($user, ['url'=>'user_profile','route'=>[$user->id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 

    <div class='form-group  @if ($errors->has('name')) has-error @endif'>
        <label for='name' class='col-sm-3 control-label'>Name<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'255']) }}
            @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('email')) has-error @endif'>
        <label for='email' class='col-sm-3 control-label'>Email<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('email', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'255']) }}
            @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
        </div>
    </div>
	<hr>
    <div class='form-group  @if ($errors->has('service_id')) has-error @endif'>
        <label for='service_id' class='col-sm-3 control-label'>Appointment Book</label>
        <div class='col-sm-9'>
            {{ Form::select('service_id', $services,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('service_id')) <p class="help-block">{{ $errors->first('service_id') }}</p> @endif
        </div>
    </div>
    <div class='form-group  @if ($errors->has('consultation_fee')) has-error @endif'>
        <label for='consultation_fee' class='col-sm-3 control-label'>Consulation Fee</label>
        <div class='col-sm-9'>
            {{ Form::text('consultation_fee', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'255']) }}
            @if ($errors->has('consultation_fee')) <p class="help-block">{{ $errors->first('consultation_fee') }}</p> @endif
        </div>
    </div>
	{{  Form::hidden('username', $user->username) }}
    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
{{ Form::close() }}

@endsection
