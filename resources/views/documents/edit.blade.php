@extends('layouts.app')

@section('content')
@include('patients.id')
<h1>
Edit Document
</h1>
@include('common.errors')
<br>
{{ Form::model($document, ['route'=>['documents.update',$document->document_id],'method'=>'PUT', 'class'=>'form-horizontal','enctype'=>'multipart/form-data']) }} 
    
	@include('documents.document')
{{ Form::close() }}

@endsection
