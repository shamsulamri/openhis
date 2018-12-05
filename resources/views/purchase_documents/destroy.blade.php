@extends('layouts.app')

@section('content')
<h1>
Delete Purchase Document
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $purchase_document->document_code }}
{{ Form::open(['url'=>'purchase_documents/'.$purchase_document->document_code, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/purchase_documents" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
