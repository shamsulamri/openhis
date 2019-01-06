@extends('layouts.app')

@section('content')
<h1>
New Purchase Request Status
</h1>
@include('common.errors')
<br>
{{ Form::model($purchase_request_status, ['url'=>'purchase_request_statuses', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('status_code')) has-error @endif'>
        <label for='status_code' class='col-sm-2 control-label'>status_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('status_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('status_code')) <p class="help-block">{{ $errors->first('status_code') }}</p> @endif
        </div>
    </div>    
    
	@include('purchase_request_statuses.purchase_request_status')
{{ Form::close() }}

@endsection
