@extends('layouts.app')

@section('content')
<h1>
Delete {{ $table }}
</h1>

<br>
<h3>
Are you sure you want to delete the selected record ?
<br>
<br>
{{ $record->id }}
{{ Form::open(['url'=>'schema/destroy', 'class'=>'pull-right']) }}
	<a class="btn btn-default" href="/schema/index/{{ $table }}" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
	{{ Form::hidden('table', $table) }}
	{{ Form::hidden('id', $id) }}
{{ Form::close() }}

</h3>
@endsection
