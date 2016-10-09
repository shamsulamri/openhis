@extends('layouts.app')

@section('content')
<h1>
New Title
</h1>
@include('common.errors')
<br>
{{ Form::model($title, ['url'=>'titles', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('title_code')) has-error @endif'>
        <label for='title_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('title_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('title_code')) <p class="help-block">{{ $errors->first('title_code') }}</p> @endif
        </div>
    </div>    
    
	@include('titles.title')
{{ Form::close() }}

@endsection
