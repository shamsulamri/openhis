@extends('layouts.app2')

@section('content')
<h2>
Post Movement
</h2>
@include('common.errors')
<br>
<h3>
Are you sure you want to post the transaction ?
{{ Form::open(['url'=>'inventories/post/'.$move_id, 'class'=>'pull-right']) }}
	{{ method_field('POST') }}
	<a class="btn btn-default" href="/inventories/line/{{ $move_id }}" role="button">Cancel</a>
	{{ Form::submit('Yes', ['class'=>'btn btn-primary']) }}
{{ Form::close() }}

</h3>
@endsection
