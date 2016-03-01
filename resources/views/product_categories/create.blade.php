@extends('layouts.app')

@section('content')
<h1>
New Product Category
</h1>
@include('common.errors')
<br>
{{ Form::model($product_category, ['url'=>'product_categories', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('category_code')) has-error @endif'>
        <label for='category_code' class='col-sm-2 control-label'>category_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('category_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('category_code')) <p class="help-block">{{ $errors->first('category_code') }}</p> @endif
        </div>
    </div>    
    
	@include('product_categories.product_category')
{{ Form::close() }}

@endsection
