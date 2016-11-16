@extends('layouts.app')

@section('content')
<h1>
Delete Postcode
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $postcode->postcode }}
{{ Form::open(['url'=>'postcodes/'.$postcode->postcode, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/postcodes" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
