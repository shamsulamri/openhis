@extends('layouts.app')

@section('content')
<h1>
Edit Product Category Parent
</h1>
@include('common.errors')
<br>
{{ Form::model($product_category_parent, ['route'=>['product_category_parents.update',$product_category_parent->parent_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-2 control-label'>parent_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::label('parent_code', $product_category_parent->parent_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('product_category_parents.product_category_parent')
{{ Form::close() }}

@endsection
