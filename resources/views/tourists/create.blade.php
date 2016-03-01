@extends('layouts.app')

@section('content')
<h1>
New Tourist
</h1>
@include('common.errors')
<br>
{{ Form::model($tourist, ['url'=>'tourists', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('tourist_code')) has-error @endif'>
        <label for='tourist_code' class='col-sm-2 control-label'>tourist_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('tourist_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('tourist_code')) <p class="help-block">{{ $errors->first('tourist_code') }}</p> @endif
        </div>
    </div>    
    
	@include('tourists.tourist')
{{ Form::close() }}

@endsection
