@extends('layouts.app')

@section('content')
<h1>
Delete Ward Class
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $ward_class->class_name }}
{{ Form::open(['url'=>'ward_classes/'.$ward_class->class_code, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/ward_classes" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
