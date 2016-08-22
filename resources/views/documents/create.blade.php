@extends('layouts.app')

@section('content')
@include('patients.id')
<h1>
New Document
</h1>
@include('common.errors')
<br>
{{ Form::model($document, ['url'=>'documents', 'class'=>'form-horizontal','enctype'=>'multipart/form-data']) }} 
    
	@include('documents.document')
{{ Form::close() }}

@endsection
