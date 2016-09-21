@extends('layouts.app')

@section('content')
<h1>
Delete Sponsor
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $sponsor->sponsor_name }}
{{ Form::open(['url'=>'sponsors/'.$sponsor->sponsor_code, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/sponsors" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
