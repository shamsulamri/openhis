@extends('layouts.app2')

@section('content')
<h3>
{{ $product_search->product_name }}
</h3>
@include('common.errors')
<br>
{{ Form::model($product_search, ['route'=>['product_searches.update',$product_search->product_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-3 control-label'>product_code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::label('product_code', $product_search->product_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('product_searches.product_search')
{{ Form::close() }}

@endsection
