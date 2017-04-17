@extends('layouts.app')

@section('content')
<h1>
Edit Team
</h1>
@include('common.errors')
<br>
{{ Form::model($team, ['route'=>['teams.update',$team->team_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-2 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::label('team_code', $team->team_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('teams.team')
{{ Form::close() }}

@endsection
