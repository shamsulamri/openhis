@extends('layouts.app')

@section('content')
<h1>
Delete Order Imaging
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $order_imaging->side }}
{{ Form::open(['url'=>'order_imaging/'.$order_imaging->product_code, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/order_imaging" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
