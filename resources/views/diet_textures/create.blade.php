@extends('layouts.app')

@section('content')
<h1>
New Diet Texture
</h1>
@include('common.errors')
<br>
{{ Form::model($diet_texture, ['url'=>'diet_textures', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('texture_code')) has-error @endif'>
        <label for='texture_code' class='col-sm-2 control-label'>texture_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('texture_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'10']) }}
            @if ($errors->has('texture_code')) <p class="help-block">{{ $errors->first('texture_code') }}</p> @endif
        </div>
    </div>    
    
	@include('diet_textures.diet_texture')
{{ Form::close() }}

@endsection
