@extends('layouts.app')

@section('content')
<h1>
New Queue Location
</h1>
@include('common.errors')
<br>
{{ Form::model($queue_location, ['url'=>'queue_locations', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('location_code')) has-error @endif'>
        <label for='location_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('location_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20.0']) }}
            @if ($errors->has('location_code')) <p class="help-block">{{ $errors->first('location_code') }}</p> @endif
        </div>
    </div>    
    
	@include('queue_locations.queue_location')
{{ Form::close() }}

@endsection
