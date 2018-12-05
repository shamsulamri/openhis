@extends('layouts.app2')

@section('content')
<h2>
Post Transaction
</h2>
@include('common.errors')
<br>
<h3>
Are you sure you want to post the transaction ?
{{ Form::open(['url'=>'purchase_lines/post/'.$id, 'class'=>'pull-right']) }}
	{{ method_field('POST') }}
	<a class="btn btn-default" href="/purchase_lines/detail/{{ $id }}" role="button">Cancel</a>
	{{ Form::submit('Yes', ['class'=>'btn btn-primary']) }}
{{ Form::close() }}

</h3>
@endsection
