@extends('layouts.app')

@section('content')
<h1>
Delete Store Authorization
</h1>

<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $store_authorization->author_id }}
{{ Form::open(['url'=>'store_authorizations/'.$store_authorization->id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/store_authorizations" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
