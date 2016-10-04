@extends('layouts.app')

@section('content')
<h1>
Edit Queue Location
</h1>
@include('common.errors')
<br>
{{ Form::model($queue_location, ['route'=>['queue_locations.update',$queue_location->location_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::label('location_code', $queue_location->location_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('queue_locations.queue_location')
{{ Form::close() }}

@endsection
