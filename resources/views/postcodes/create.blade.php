@extends('layouts.app')

@section('content')
<h1>
New Postcode
</h1>
@include('common.errors')
<br>
{{ Form::model($postcode, ['url'=>'postcodes', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('postcode')) has-error @endif'>
        <label for='postcode' class='col-sm-2 control-label'>postcode<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('postcode', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'0']) }}
            @if ($errors->has('postcode')) <p class="help-block">{{ $errors->first('postcode') }}</p> @endif
        </div>
    </div>    
    
	@include('postcodes.postcode')
{{ Form::close() }}

@endsection
