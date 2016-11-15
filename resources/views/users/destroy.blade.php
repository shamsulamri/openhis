@extends('layouts.app')

@section('content')
<h1>
Delete User
</h1>

<br>
<h3>
Are you sure you want to delete the selected record ?
<br>
<br>
{{ $user->name }}
<br>
<br>
{{ Form::open(['url'=>'users/'.$user->id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/users" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
