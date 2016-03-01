@extends('layouts.app')

@section('content')
<h1>
Delete Birth Type
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $birth_type->birth_name }}
{{ Form::open(['url'=>'birth_types/'.$birth_type->birth_code, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/birth_types" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
