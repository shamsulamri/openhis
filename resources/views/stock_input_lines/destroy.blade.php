@extends('layouts.app2')

@section('content')
<h3>
Delete Line Item
</h3>
@include('common.errors')
{{ Form::open(['url'=>'stock_input_lines/'.$stock_input_line->line_id]) }}
{{ method_field('DELETE') }}
Are you sure you want to delete the selected record ?
<br>
<br>
<strong>
{{ $stock_input_line->product->product_name }}
</strong>
	<br>
	<br>
	<a class="btn btn-default" href="/stock_inputs/input/{{ $stock_input_line->input_id }}" role="button">Cancel</a>
	{{ Form::submit('Delete', ['class'=>'btn btn-danger']) }}
{{ Form::close() }}

@endsection
