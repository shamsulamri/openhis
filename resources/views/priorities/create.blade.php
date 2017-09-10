@extends('layouts.app')

@section('content')
<h1>
New Priority
</h1>
@include('common.errors')
<br>
{{ Form::model($priority, ['url'=>'priorities', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('priority_code')) has-error @endif'>
        <label for='priority_code' class='col-sm-2 control-label'>priority_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('priority_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('priority_code')) <p class="help-block">{{ $errors->first('priority_code') }}</p> @endif
        </div>
    </div>    
    
	@include('priorities.priority')
{{ Form::close() }}

@endsection
