@extends('layouts.app')

@section('content')
<h1>
Delete Stock Receive
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $stock_receive->purchase_id }}
{{ Form::open(['url'=>'stock_receives/'.$stock_receive->receive_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/stock_receives" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
