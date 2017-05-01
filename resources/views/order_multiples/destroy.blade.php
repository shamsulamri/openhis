@extends('layouts.app')

@section('content')
<h1>
Delete Order Multiple
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $order_multiple->order_id }}
{{ Form::open(['url'=>'order_multiples/'.$order_multiple->multiple_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/order_multiples" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
