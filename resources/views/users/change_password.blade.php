@extends('layouts.app')

@section('content')
<h1>Change Password</h1>
<br>
@include('common.errors')
@include('common.notification')
{{ Form::model($user->user_id, ['url'=>'change_password', 'class'=>'form-horizontal']) }} 

    <div class='form-group  @if ($errors->has('current_password')) has-error @endif'>
        <label for='pass' class='col-sm-4 control-label'>Current Password</label>
        <div class='col-sm-4'>
			<input id="current_password" type="password" class="form-control" name="current_password">
            @if ($errors->has('current_password')) <p class="help-block">{{ $errors->first('current_password') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('new_password')) has-error @endif'>
        <label for='pass' class='col-sm-4 control-label'>New Password</label>
        <div class='col-sm-4'>
			<input id="new_password" type="password" class="form-control" name="new_password">
            @if ($errors->has('new_password')) <p class="help-block">{{ $errors->first('new_password') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('verify_password')) has-error @endif'>
        <label for='pass' class='col-sm-4 control-label'>Verify Password</label>
        <div class='col-sm-4'>
			<input id="verify_password" type="password" class="form-control" name="verify_password">
            @if ($errors->has('verify_password')) <p class="help-block">{{ $errors->first('verify_password') }}</p> @endif
        </div>
    </div>

	<div class='form-group'>
        <div class="col-sm-offset-4 col-sm-8">
            {{ Form::submit('Submit', ['class'=>'btn btn-primary']) }}
        </div>
    </div>


	<div class='page-header'>
	</div>
<h4>Password contains characters from at least three of the following five categories:</h4>
<br>
<h5>- English uppercase characters (A – Z)</h5>
<h5>- English lowercase characters (a – z)</h5>
<h5>- Base 10 digits (0 – 9)</h5>
<h5>- Non-alphanumeric (For example: !, $, #, or %)</h5>
<h5>- Unicode character</h5>

{{ Form::close() }}

@endsection
