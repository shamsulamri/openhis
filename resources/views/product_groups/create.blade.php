@extends('layouts.app')

@section('content')
<h1>
New Product Group
</h1>
@include('common.errors')
<br>
{{ Form::model($product_group, ['url'=>'product_groups', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('group_code')) has-error @endif'>
        <label for='group_code' class='col-sm-2 control-label'>group_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('group_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('group_code')) <p class="help-block">{{ $errors->first('group_code') }}</p> @endif
        </div>
    </div>    
    
	@include('product_groups.product_group')
{{ Form::close() }}

@endsection
