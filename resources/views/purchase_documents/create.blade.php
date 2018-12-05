@extends('layouts.app')

@section('content')
<h1>
New Purchase Document
</h1>
@include('common.errors')
<br>
{{ Form::model($purchase_document, ['url'=>'purchase_documents', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('document_code')) has-error @endif'>
        <label for='document_code' class='col-sm-2 control-label'>document_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('document_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('document_code')) <p class="help-block">{{ $errors->first('document_code') }}</p> @endif
        </div>
    </div>    
    
	@include('purchase_documents.purchase_document')
{{ Form::close() }}

@endsection
