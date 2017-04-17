@extends('layouts.app')

@section('content')
<h1>
New Team
</h1>
@include('common.errors')
<br>
{{ Form::model($team, ['url'=>'teams', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('team_code')) has-error @endif'>
        <label for='team_code' class='col-sm-2 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('team_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('team_code')) <p class="help-block">{{ $errors->first('team_code') }}</p> @endif
        </div>
    </div>    
    
	@include('teams.team')
{{ Form::close() }}

@endsection
