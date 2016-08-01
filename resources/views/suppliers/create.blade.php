@extends('layouts.app')

@section('content')
<h1>
New Supplier
</h1>
@include('common.errors')
<br>
{{ Form::model($supplier, ['url'=>'suppliers', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('supplier_code')) has-error @endif'>
        <label for='supplier_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('supplier_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('supplier_code')) <p class="help-block">{{ $errors->first('supplier_code') }}</p> @endif
        </div>
    </div>    
    
	@include('suppliers.supplier')
{{ Form::close() }}

@endsection
