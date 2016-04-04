@extends('layouts.app')

@section('content')
<h1>
New Relationship
</h1>
@include('common.errors')
<br>
{{ Form::model($relationship, ['url'=>'relationships', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('relation_code')) has-error @endif'>
        <label for='relation_code' class='col-sm-2 control-label'>relation_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('relation_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('relation_code')) <p class="help-block">{{ $errors->first('relation_code') }}</p> @endif
        </div>
    </div>    
    
	@include('relationships.relationship')
{{ Form::close() }}

@endsection