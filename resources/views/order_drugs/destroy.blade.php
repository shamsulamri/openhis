@extends('layouts.app')

@section('content')
<h1>
Delete Order Drug
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $order_drug->drug_strength }}
{{ Form::open(['url'=>'order_drugs/'.$order_drug->id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/order_drugs" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
