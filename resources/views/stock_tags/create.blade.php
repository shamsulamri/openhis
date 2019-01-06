@extends('layouts.app')

@section('content')
<h1>
New Stock Tag
</h1>
@include('common.errors')
<br>
{{ Form::model($stock_tag, ['url'=>'stock_tags', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('tag_code')) has-error @endif'>
        <label for='tag_code' class='col-sm-2 control-label'>tag_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('tag_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('tag_code')) <p class="help-block">{{ $errors->first('tag_code') }}</p> @endif
        </div>
    </div>    
    
	@include('stock_tags.stock_tag')
{{ Form::close() }}

@endsection
