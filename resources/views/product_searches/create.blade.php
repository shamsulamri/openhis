@extends('layouts.app')

@section('content')
<h1>
New Product Search
</h1>
@include('common.errors')
<br>
{{ Form::model($product_search, ['url'=>'product_searches', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('product_code')) has-error @endif'>
        <label for='product_code' class='col-sm-3 control-label'>product_code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('product_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20.0']) }}
            @if ($errors->has('product_code')) <p class="help-block">{{ $errors->first('product_code') }}</p> @endif
        </div>
    </div>    
    
	@include('product_searches.product_search')
{{ Form::close() }}

@endsection
