@extends('layouts.app')

@section('content')
<h1>
Delete Discharge Type
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $discharge_type->type_name }}
{{ Form::open(['url'=>'discharge_types/'.$discharge_type->type_code, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/discharge_types" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
