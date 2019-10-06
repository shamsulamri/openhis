@extends('layouts.app')

@section('content')
@include('products.id')
<h1>Edit Store Keeping Unit</h1>
@include('common.errors')
<br>
{{ Form::model($product_uom, ['route'=>['product_uoms.update',$product_uom->id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('unit_code')) has-error @endif'>
        <label for='unit_code' class='col-sm-2 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
{{ $product_uom->unit_code }}
            {{ Form::label('unit_code', $product_uom->unitMeasure->unit_name, ['class'=>'control-label']) }}
        </div>
    </div>
	@include('product_uoms.product_uom')
{{ Form::close() }}

@endsection
