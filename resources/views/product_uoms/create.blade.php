@extends('layouts.app')

@section('content')
@include('products.id')
<h1>New Unit of Measure</h1>
@include('common.errors')
<br>
{{ Form::model($product_uom, ['url'=>'product_uoms', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group  @if ($errors->has('unit_code')) has-error @endif'>
        <label for='unit_code' class='col-sm-2 control-label'>Unit<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::select('unit_code', $unit,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('unit_code')) <p class="help-block">{{ $errors->first('unit_code') }}</p> @endif
        </div>
    </div>

	@include('product_uoms.product_uom')
{{ Form::close() }}

@endsection
