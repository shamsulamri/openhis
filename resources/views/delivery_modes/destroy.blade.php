@extends('layouts.app')

@section('content')
<h1>
Delete Delivery Mode
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $delivery_mode->delivery_name }}
{{ Form::open(['url'=>'delivery_modes/'.$delivery_mode->delivery_code, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/delivery_modes" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
