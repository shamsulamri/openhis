@extends('layouts.app')

@section('content')
@include('patients.id')
<h1>New Encounter</h1>
@include('common.errors')
{{ Form::model($queue, ['url'=>'queues', 'class'=>'form-horizontal']) }} 
	@include('queues.queue')
	<br>
    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/patients/{{ $encounter->patient_id }}" role="button">Cancel</a>
            {{ Form::submit('Save', ['id'=>'save','class'=>'btn btn-primary']) }}
        </div>
    </div>
{{ Form::close() }}

@endsection
