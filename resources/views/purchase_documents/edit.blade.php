@extends('layouts.app')

@section('content')
<h1>
Edit Purchase Document
</h1>
@include('common.errors')
<br>
{{ Form::model($purchase_document, ['route'=>['purchase_documents.update',$purchase_document->document_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-2 control-label'>document_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::label('document_code', $purchase_document->document_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('purchase_documents.purchase_document')
{{ Form::close() }}

@endsection
