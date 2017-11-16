@extends('layouts.app')

@section('content')
<h1>
Delete Consultation Annotation
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $consultation_annotation->consultation_id }}
{{ Form::open(['url'=>'consultation_annotations/'.$consultation_annotation->annotation_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/consultation_annotations" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
