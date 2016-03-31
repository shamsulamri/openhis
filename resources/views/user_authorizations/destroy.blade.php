@extends('layouts.app')

@section('content')
<h1>
Delete User Authorization
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $user_authorization->author_consultation }}
{{ Form::open(['url'=>'user_authorizations/'.$user_authorization->id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/user_authorizations" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
