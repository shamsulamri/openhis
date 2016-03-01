@extends('layouts.app')

@section('content')
<h1>
New Queue
</h1>
@include('common.errors')
<br>
{{ Form::model($queue, ['url'=>'queues', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('queue_id')) has-error @endif'>
        <label for='queue_id' class='col-sm-2 control-label'>queue_id<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('queue_id', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20.0']) }}
            @if ($errors->has('queue_id')) <p class="help-block">{{ $errors->first('queue_id') }}</p> @endif
        </div>
    </div>    
    
	@include('queues.queue')
{{ Form::close() }}

@endsection
