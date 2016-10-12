@extends('layouts.app')

@section('content')
<h1>
User Profile
</h1>
<br>
@include('common.errors')
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
	{{  Form::hidden('username', $user->username) }}
    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
{{ Form::close() }}

@endsection
