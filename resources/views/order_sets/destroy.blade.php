@extends('layouts.app2')

@section('content')
<h3>
Delete Asset
</h3>
@include('common.errors')
<br>
<h4>
Are you sure you want to delete the selected record ?
<br>
<br>
{{ $order_set->product->product_name }}
<br>
<br>
{{ Form::open(['url'=>'order_sets/'.$order_set->id]) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/order_sets/index/{{ $order_set->set_code }}" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h4>
@endsection
