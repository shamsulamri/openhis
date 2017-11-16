@extends('layouts.app')

@section('content')
<h1>
New Consultation Annotation
</h1>
@include('common.errors')
<br>
{{ Form::model($consultation_annotation, ['url'=>'consultation_annotations', 'class'=>'form-horizontal']) }} 
    
	@include('consultation_annotations.consultation_annotation')
{{ Form::close() }}

@endsection
