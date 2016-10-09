@extends('layouts.app')

@section('content')
<h1>
New Document Status
</h1>
@include('common.errors')
<br>
{{ Form::model($document_status, ['url'=>'document_statuses', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('status_code')) has-error @endif'>
        <label for='status_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('status_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('status_code')) <p class="help-block">{{ $errors->first('status_code') }}</p> @endif
        </div>
    </div>    
    
	@include('document_statuses.document_status')
{{ Form::close() }}

@endsection
