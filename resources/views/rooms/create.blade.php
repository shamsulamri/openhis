@extends('layouts.app')

@section('content')
<h1>
New Room
</h1>
@include('common.errors')
<br>
{{ Form::model($room, ['url'=>'rooms', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('room_code')) has-error @endif'>
        <label for='room_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('room_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'10']) }}
            @if ($errors->has('room_code')) <p class="help-block">{{ $errors->first('room_code') }}</p> @endif
        </div>
    </div>    
    
	@include('rooms.room')
{{ Form::close() }}

@endsection
