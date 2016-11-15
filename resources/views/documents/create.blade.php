@extends('layouts.app')

@section('content')
@include('patients.id')
<h1>
New Document
</h1>

<br>
{{ Form::model($document, ['url'=>'documents', 'class'=>'form-horizontal','enctype'=>'multipart/form-data']) }} 
    
	@include('documents.document')
{{ Form::close() }}

@endsection
