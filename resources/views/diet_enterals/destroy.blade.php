@extends('layouts.app')

@section('content')
<h1>
Delete Diet Enteral
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $diet_enteral->enteral_name }}
{{ Form::open(['url'=>'diet_enterals/'.$diet_enteral->enteral_code, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/diet_enterals" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
