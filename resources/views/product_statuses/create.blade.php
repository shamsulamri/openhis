@extends('layouts.app')

@section('content')
<h1>
New Product Status
</h1>
@include('common.errors')
<br>
{{ Form::model($product_status, ['url'=>'product_statuses', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('status_code')) has-error @endif'>
        <label for='status_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('status_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('status_code')) <p class="help-block">{{ $errors->first('status_code') }}</p> @endif
        </div>
    </div>    
    
	@include('product_statuses.product_status')
{{ Form::close() }}

@endsection
