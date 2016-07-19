@extends('layouts.app')

@section('content')
@include('products.id')
<h1>
Delete Product Maintenance
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $product_maintenance->maintain_datetime }}
{{ Form::open(['url'=>'product_maintenances/'.$product_maintenance->maintain_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/product_maintenances" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
