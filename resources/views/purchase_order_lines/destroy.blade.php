@extends('layouts.app')

@section('content')
<h1>
Delete Purchase Order Line
</h1>
@include('common.errors')
<br>
<h3>
Are you sure you want to delete the selected record ?
{{ $purchase_order_line->purchase_id }}
{{ Form::open(['url'=>'purchase_order_lines/'.$purchase_order_line->line_id, 'class'=>'pull-right']) }}
	{{ method_field('DELETE') }}
	<a class="btn btn-default" href="/purchase_order_lines" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

</h3>
@endsection
