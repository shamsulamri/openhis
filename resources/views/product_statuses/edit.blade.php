@extends('layouts.app')

@section('content')
<h1>
Edit Product Status
</h1>
@include('common.errors')
<br>
{{ Form::model($product_status, ['route'=>['product_statuses.update',$product_status->status_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::label('status_code', $product_status->status_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('product_statuses.product_status')
{{ Form::close() }}

@endsection
