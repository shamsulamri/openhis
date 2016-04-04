@extends('layouts.app')

@section('content')
<h1>
New Store
</h1>
@include('common.errors')
<br>
{{ Form::model($store, ['url'=>'stores', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('store_code')) has-error @endif'>
        <label for='store_code' class='col-sm-2 control-label'>store_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('store_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20.0']) }}
            @if ($errors->has('store_code')) <p class="help-block">{{ $errors->first('store_code') }}</p> @endif
        </div>
    </div>    
    
	@include('stores.store')
{{ Form::close() }}

@endsection