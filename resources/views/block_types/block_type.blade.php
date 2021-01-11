@extends('layouts.app')

@section('content')
<h1>
@if (empty($block_type))
	New Block Type
@else
	Edit Block Type
@endif
</h1>
<br>

@if (empty($block_type))
	{{ Form::model($block_type, ['url'=>'block_types', 'class'=>'form-horizontal']) }} 
@else
	{{ Form::model($block_type, ['route'=>['block_types.update',$block_type->block_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
@endif
    
    <div class='form-group @if ($errors->has('block_code')) has-error @endif'>
        <label for='block_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
		@if (empty($block_type))
            {{ Form::text('block_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
		@else
            {{ Form::label('block_code', $block_type->block_code, ['class'=>'form-control']) }}
		@endif
            @if ($errors->has('block_code')) <p class="help-block">{{ $errors->first('block_code') }}</p> @endif
        </div>
    </div>    
    
    <div class='form-group @if ($errors->has('block_name')) has-error @endif'>
        <label for='block_name' class='col-sm-3 control-label'>Name</label>
        <div class='col-sm-9'>
            {{ Form::text('block_name', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'200']) }}
            @if ($errors->has('block_name')) <p class="help-block">{{ $errors->first('block_name') }}</p> @endif
        </div>
    </div>    

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/block_types" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary', 'name'=>'submitButton']) }}
        </div>
    </div>


{{ Form::close() }}

@endsection
