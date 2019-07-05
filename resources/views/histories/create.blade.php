@extends('layouts.app')

@section('content')
<h1>
New History
</h1>
@include('common.errors')
<br>
{{ Form::model($history, ['url'=>'histories', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('history_code')) has-error @endif'>
        <label for='history_code' class='col-sm-2 control-label'>history_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('history_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20.0']) }}
            @if ($errors->has('history_code')) <p class="help-block">{{ $errors->first('history_code') }}</p> @endif
        </div>
    </div>    
    
	@include('histories.history')
{{ Form::close() }}

@endsection
