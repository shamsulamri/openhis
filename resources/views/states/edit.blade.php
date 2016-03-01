@extends('layouts.app')

@section('content')
<h1>
Edit State
</h1>
@include('common.errors')
<br>
{{ Form::model($state, ['route'=>['states.update',$state->state_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-2 control-label'>state_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::label('state_code', $state->state_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('states.state')
{{ Form::close() }}

@endsection
