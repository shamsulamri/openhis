@extends('layouts.app')

@section('content')
<h1>
New Product Category Parent
</h1>
@include('common.errors')
<br>
{{ Form::model($product_category_parent, ['url'=>'product_category_parents', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('parent_code')) has-error @endif'>
        <label for='parent_code' class='col-sm-2 control-label'>parent_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('parent_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20.0']) }}
            @if ($errors->has('parent_code')) <p class="help-block">{{ $errors->first('parent_code') }}</p> @endif
        </div>
    </div>    
    
	@include('product_category_parents.product_category_parent')
{{ Form::close() }}

@endsection
