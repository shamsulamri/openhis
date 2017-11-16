@extends('layouts.app')

@section('content')
<h1>
Edit Consultation Annotation
</h1>
@include('common.errors')
<br>
{{ Form::model($consultation_annotation, ['route'=>['consultation_annotations.update',$consultation_annotation->annotation_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('consultation_annotations.consultation_annotation')
{{ Form::close() }}

@endsection
