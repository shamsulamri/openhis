@extends('layouts.app')

@section('content')
<h1>
Edit Diet Texture
</h1>
@include('common.errors')
<br>
{{ Form::model($diet_texture, ['route'=>['diet_textures.update',$diet_texture->texture_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::label('texture_code', $diet_texture->texture_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('diet_textures.diet_texture')
{{ Form::close() }}

@endsection
